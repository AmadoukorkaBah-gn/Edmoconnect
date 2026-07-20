<?php

namespace App\Services;

use App\Models\MikrotikServer;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;
use Exception;

class MikrotikService
{
    protected MikrotikServer $server;
    protected ?Client $client = null;

    public function __construct(MikrotikServer $server)
    {
        $this->server = $server;
    }

    /**
     * Établit la connexion au routeur.
     */
    public function connect(): Client
    {
        if ($this->client) {
            return $this->client;
        }

        $config = new Config([
            'host' => $this->server->host,
            'user' => $this->server->username,
            'pass' => $this->server->password, // déchiffré automatiquement via le cast 'encrypted'
            'port' => $this->server->port,
            'ssl' => (bool) $this->server->ssl,
            'timeout' => 5,
        ]);

        $this->client = new Client($config);

        return $this->client;
    }

    /**
     * Teste simplement si la connexion fonctionne.
     */
    public function testConnection(): array
    {
        try {
            $client = $this->connect();

            $query = new Query('/system/identity/print');
            $response = $client->query($query)->read();

            return [
                'success' => true,
                'identity' => $response[0]['name'] ?? null,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Crée un utilisateur hotspot temporaire (utilisé plus tard pour les abonnements).
     */
    /**
 * Cree un utilisateur hotspot avec une limite de duree.
 */
public function createHotspotUser(string $username, string $password, string $profile, int $limitUptimeHeures): array
{
    try {
        $client = $this->connect();

        $query = (new Query('/ip/hotspot/user/add'))
            ->equal('name', $username)
            ->equal('password', $password)
            ->equal('profile', $profile)
            ->equal('limit-uptime', $limitUptimeHeures . ':00:00');

        $response = $client->query($query)->read();

        // RouterOS renvoie parfois une erreur metier (!trap) sans lever d'exception PHP.
        // On verifie explicitement la presence d'un message d'erreur dans la reponse.
        if (isset($response['after']['message'])) {
            return [
                'success' => false,
                'error' => $response['after']['message'],
            ];
        }

        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
/**
 * Desactive un utilisateur hotspot et coupe sa session active immediatement.
 */
public function disableHotspotUser(string $username): array
{
    try {
        $client = $this->connect();

        // Desactive le compte pour empecher toute nouvelle connexion
        $findQuery = (new Query('/ip/hotspot/user/print'))
            ->where('name', $username);
        $users = $client->query($findQuery)->read();

        if (empty($users)) {
            return ['success' => false, 'error' => 'Utilisateur introuvable sur le routeur'];
        }

        $userId = $users[0]['.id'];

        $disableQuery = (new Query('/ip/hotspot/user/disable'))
            ->equal('.id', $userId);
        $client->query($disableQuery)->read();

        // Coupe la session active en cours, si le client est connecte maintenant
        $activeQuery = (new Query('/ip/hotspot/active/print'))
            ->where('user', $username);
        $activeSessions = $client->query($activeQuery)->read();

        foreach ($activeSessions as $session) {
            $removeQuery = (new Query('/ip/hotspot/active/remove'))
                ->equal('.id', $session['.id']);
            $client->query($removeQuery)->read();
        }

        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
}
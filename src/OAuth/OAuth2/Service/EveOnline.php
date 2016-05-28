<?php
/**
 * Contains EveOnline class.
 * PHP version 5.4
 * @copyright 2014 Michael Cummings
 * @author    Michael Cummings <mgcummings@yahoo.com>
 */
namespace OAuth\OAuth2\Service;

use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Http\Uri\UriInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Token\TokenInterface;
use OAuth\OAuth2\Token\StdOAuth2Token;

/**
 * Class EveOnline
 */
class EveOnline extends AbstractService
{
    /**
     * Read your account subscription status.
     */
    const SCOPE_CHARACTER_ACCOUNT_READ = 'characterAccountRead';

    /**
     * Read your asset list.
     */
    const SCOPE_CHARACTER_ASSETS_READ = 'characterAssetsRead';

    /**
     * List your bookmarks and their coordinates.
     */
    const SCOPE_CHARACTER_BOOKMARKS_READ = 'characterBookmarksRead';

    /**
     * Read your calendar events and attendees.
     */
    const SCOPE_CHARACTER_CALENDAR_READ = 'characterCalendarRead';

    /**
     * List chat channels you own or operate.
     */
    const SCOPE_CHARACTER_CHAT_CHANNELS_READ = 'characterChatChannelsRead';

    /**
     * List your jump clones, implants, attributes, and jump fatigue timer.
     */
    const SCOPE_CHARACTER_CLONES_READ = 'characterClonesRead';

    /**
     * Allows access to reading your characters contacts.
     */
    const SCOPE_CHARACTER_CONTACTS_READ = 'characterContactsRead';

    /**
     * Allows applications to add, modify, and delete contacts for your character.
     */
    const SCOPE_CHARACTER_CONTACTS_WRITE = 'characterContactsWrite';

    /**
     * Read your contracts.
     */
    const SCOPE_CHARACTER_CONTRACTS_READ = 'characterContractsRead';

    /**
     * Read your factional warfare statistics.
     */
    const SCOPE_CHARACTER_FACTIONAL_WARFARE_READ = 'characterFactionalWarfareRead';

    /**
     * Allows an application to view all of your character's saved fits.
     */
    const SCOPE_CHARACTER_FITTINGS_READ = 'characterFittingsRead';

    /**
     * Allows an application to create and delete the saved fits for your character.
     */
    const SCOPE_CHARACTER_FITTINGS_WRITE = 'characterFittingsWrite';

    /**
     * List your industry jobs.
     */
    const SCOPE_CHARACTER_INDUSTRY_JOBS_READ = 'characterIndustryJobsRead';

    /**
     * Read your kill mails.
     */
    const SCOPE_CHARACTER_KILLS_READ = 'characterKillsRead';

    /**
     * Allows an application to read your characters real time location in EVE.
     */
    const SCOPE_CHARACTER_LOCATION_READ = 'characterLocationRead';

    /**
     * List loyalty points your character has for the different corporations.
     */
    const SCOPE_CHARACTER_LOYALTY_POINTS_READ = 'characterLoyaltyPointsRead';

    /**
     * Read your EVE Mail.
     */
    const SCOPE_CHARACTER_MAIL_READ = 'characterMailRead';

    /**
     * Read your market orders.
     */
    const SCOPE_CHARACTER_MARKET_ORDERS_READ = 'characterMarketOrdersRead';

    /**
     * List your public and private medals.
     */
    const SCOPE_CHARACTER_MEDALS_READ = 'characterMedalsRead';

    /**
     * Allows an application to set your ships autopilot destination.
     */
    const SCOPE_CHARACTER_NAVIGATION_WRITE = 'characterNavigationWrite';

    /**
     * Receive in-game notifications.
     */
    const SCOPE_CHARACTER_NOTIFICATIONS_READ = 'characterNotificationsRead';

    /**
     * List the opportunities your character has completed.
     */
    const SCOPE_CHARACTER_OPPORTUNITIES_READ = 'characterOpportunitiesRead';

    /**
     * List your research agents working for you and research progress.
     */
    const SCOPE_CHARACTER_RESEARCH_READ = 'characterResearchRead';

    /**
     * Read your skills and skill queue.
     */
    const SCOPE_CHARACTER_SKILLS_READ = 'characterSkillsRead';

    /**
     * Yearly aggregated stats about your character.
     */
    const SCOPE_CHARACTER_STATS_READ = 'characterStatsRead';

    /**
     * Read your wallet status, transaction, and journal history.
     */
    const SCOPE_CHARACTER_WALLET_READ = 'characterWalletRead';

    /**
     * Read your corporation's asset list.
     */
    const SCOPE_CORPORATION_ASSET_READ = 'corporationAssetRead';

    /**
     * List your corporation's bookmarks and their coordinates.
     */
    const SCOPE_CORPORATION_BOOKMARKS_READ = 'corporationBookmarksRead';

    /**
     * List your corporation's contracts.
     */
    const SCOPE_CORPORATION_CONTRACTS_READ = 'corporationContractsRead';

    /**
     * Read your corporation's factional warfare statistics.
     */
    const SCOPE_CORPORATION_FACTIONAL_WARFARE_READ = 'corporationFactionalWarfareRead';

    /**
     * List your corporation's industry jobs.
     */
    const SCOPE_CORPORATION_INDUSTRY_JOBS_READ = 'corporationIndustryJobsRead';

    /**
     * Read your corporation's kill mails.
     */
    const SCOPE_CORPORATION_KILLS_READ = 'corporationKillsRead';

    /**
     * List your corporation's market orders.
     */
    const SCOPE_CORPORATION_MARKET_ORDERS_READ = 'corporationMarketOrdersRead';

    /**
     * List your corporation's issued medals.
     */
    const SCOPE_CORPORATION_MEDALS_READ = 'corporationMedalsRead';

    /**
     * List your corporation's members, their titles, and roles.
     */
    const SCOPE_CORPORATION_MEMBERS_READ = 'corporationMembersRead';

    /**
     * List your corporation's shareholders and their shares.
     */
    const SCOPE_CORPORATION_SHAREHOLDERS_READ = 'corporationShareholdersRead';

    /**
     * List your corporation's structures, outposts, and starbases.
     */
    const SCOPE_CORPORATION_STRUCTURES_READ = 'corporationStructuresRead';

    /**
     * Read your corporation's wallet status, transaction, and journal history.
     */
    const SCOPE_CORPORATION_WALLET_READ = 'corporationWalletRead';

    /**
     * Allows real time reading of your fleet information (members, ship types, etc.) if you're the boss of the fleet.
     */
    const SCOPE_FLEET_READ = 'fleetRead';

    /**
     * Allows the ability to invite, kick, and update fleet information if you're the boss of the fleet.
     */
    const SCOPE_FLEET_WRITE = 'fleetWrite';

    /**
     * Allows access to public data.
     */
    const SCOPE_PUBLIC_DATA = 'publicData';

    /**
     * Allows updating your structures' vulnerability timers.
     */
    const SCOPE_STRUCTURE_VULN_UPDATE = 'structureVulnUpdate';

    public function __construct(
        CredentialsInterface $credentials,
        ClientInterface $httpClient,
        TokenStorageInterface $storage,
        $scopes = array(),
        UriInterface $baseApiUri = null
    ) {
        parent::__construct($credentials, $httpClient, $storage, $scopes, $baseApiUri);

        if (null === $baseApiUri) {
            $this->baseApiUri = new Uri('https://login.eveonline.com');
        }
    }

    /**
     * Returns the authorization API endpoint.
     * @return UriInterface
     */
    public function getAuthorizationEndpoint()
    {
        return new Uri($this->baseApiUri . '/oauth/authorize');
    }

    /**
     * Returns the access token API endpoint.
     * @return UriInterface
     */
    public function getAccessTokenEndpoint()
    {
        return new Uri($this->baseApiUri . '/oauth/token');
    }

    /**
     * Parses the access token response and returns a TokenInterface.
     *
     * @param string $responseBody
     *
     * @return TokenInterface
     * @throws TokenResponseException
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        $data = json_decode($responseBody, true);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data['error_description'])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data['error_description'] . '"');
        } elseif (isset($data['error'])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data['error'] . '"');
        }

        $token = new StdOAuth2Token();
        $token->setAccessToken($data['access_token']);
        $token->setLifeTime($data['expires_in']);

        if (isset($data['refresh_token'])) {
            $token->setRefreshToken($data['refresh_token']);
            unset($data['refresh_token']);
        }

        unset($data['access_token']);
        unset($data['expires_in']);

        $token->setExtraParams($data);

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthorizationMethod()
    {
        return static::AUTHORIZATION_METHOD_HEADER_BEARER;
    }
}

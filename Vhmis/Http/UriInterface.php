<?php

namespace Vhmis\Http;

/**
 * Uri interface.
 *
 * PSR7 Http Message.
 */
interface UriInterface
{
    /**
     * Retrieve the URI scheme.
     *
     * @return string The scheme of the URI.
     */
    public function getScheme();

    /**
     * Retrieve the authority portion of the URI.
     *
     * @return string Authority portion of the URI, in "[user-info@]host[:port]" format.
     */
    public function getAuthority();

    /**
     * Retrieve the user information portion of the URI, if present.
     *
     * @return string User information portion of the URI, if present, in "username[:password]" format.
     */
    public function getUserInfo();

    /**
     * Retrieve the host segment of the URI.
     *
     * @return string Host segment of the URI.
     */
    public function getHost();

    /**
     * Retrieve the port segment of the URI.
     *
     * @return null|int The port for the URI.
     */
    public function getPort();

    /**
     * Retrieve the path segment of the URI.
     *
     * @return string The path segment of the URI.
     */
    public function getPath();

    /**
     * Retrieve the query string of the URI.
     *
     * @return string The URI query string.
     */
    public function getQuery();

    /**
     * Retrieve the fragment segment of the URI.
     *
     * @return string The URI fragment.
     */
    public function getFragment();

    /**
     * Create a new instance with the specified scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     *
     * @return self A new instance with the specified scheme.
     *
     * @throws \InvalidArgumentException for invalid or unsupported schemes.
     */
    public function withScheme($scheme);

    /**
     * Create a new instance with the specified user information.
     *
     * @param string $user User name to use for authority.
     * @param null|string $password Password associated with $user.
     *
     * @return self A new instance with the specified user information.
     */
    public function withUserInfo($user, $password = null);

    /**
     * Create a new instance with the specified host.
     *
     * @param string $host Hostname to use with the new instance.
     *
     * @return self A new instance with the specified host.
     *
     * @throws \InvalidArgumentException for invalid hostnames.
     */
    public function withHost($host);

    /**
     * Create a new instance with the specified port.
     *
     * @param null|int $port Port to use with the new instance; a null value removes the port information.
     *
     * @return self A new instance with the specified port.
     *
     * @throws \InvalidArgumentException for invalid ports.
     */
    public function withPort($port);

    /**
     * Create a new instance with the specified path.
     *
     * @param string $path The path to use with the new instance.
     *
     * @return self A new instance with the specified path.
     *
     * @throws \InvalidArgumentException for invalid paths.
     */
    public function withPath($path);

    /**
     * Create a new instance with the specified query string.
     *
     * @param string $query The query string to use with the new instance.
     *
     * @return self A new instance with the specified query string.
     *
     * @throws \InvalidArgumentException for invalid query strings.
     *
     */
    public function withQuery($query);

    /**
     * Create a new instance with the specified URI fragment.
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The URI fragment to use with the new instance.
     *
     * @return self A new instance with the specified URI fragment.
     */
    public function withFragment($fragment);

    /**
     * Return the string representation of the URI.
     *
     * @return string
     */
    public function __toString();
}

<?php namespace Application\Http\Uri;

/**
 * @package    Application
 * @subpackage Application\Http\Uri
 * @object     Application\Http\Uri\Uri
 * @author     Kerem! <qeremy@gmail>
 */
final class Uri
{
    /**
     * URI source.
     * @var mixed
     */
    private $source;

    /**
     * URI scheme
     * @var string
     */
    private $scheme;

    /**
     * URI host.
     * @var string
     */
    private $host;

    /**
     * URI port.
     * @var int
     */
    private $port;

    /**
     * URI user.
     * @var string
     */
    private $user;

    /**
     * URI pass.
     * @var string
     */
    private $pass;

    /**
     * URI path.
     * @var string
     */
    private $path;

    /**
     * URI query.
     * @var string
     */
    private $query;

    /**
     * URI fragment.
     * @var string
     */
    private $fragment;

    /**
     * URI (path) Segments.
     * @var array
     */
    private $segments = [];

    /**
     * Object constructor.
     *
     * @param mixed $source
     */
    final public function __construct($source = null) {
        // set source
        $this->source = $source;

        // check source
        if (is_string($source)) {
            $source = parse_url($source);
        }

        // set properties
        if (!empty($source)) {
            isset($source['scheme']) &&
                $this->setScheme($source['scheme']);
            isset($source['host']) &&
                $this->setHost($source['host']);
            isset($source['port']) &&
                $this->setPort((int) $source['port']);
            isset($source['user']) &&
                $this->setUser($source['user']);
            isset($source['pass']) &&
                $this->setPass($source['pass']);
            isset($source['path']) &&
                $this->setPath($source['path']);
            isset($source['query']) &&
                $this->setQuery($source['query']);
            isset($source['fragment']) &&
                $this->setFragment($source['fragment']);
        }
    }

    /**
     * Get URI as string.
     *
     * @return string
     */
    final public function __toString() {
        return $this->toString();
    }

    /**
     * Set scheme.
     *
     * @param  string $scheme
     * @return self
     */
    final public function setScheme($scheme) {
        if ($scheme = trim($scheme)) {
            $this->scheme = $scheme;
        }

        return $this;
    }

    /**
     * Set host.
     *
     * @param  string $host
     * @return self
     */
    final public function setHost($host) {
        if ($host = trim($host)) {
            $this->host = $host;
        }

        return $this;
    }

    /**
     * Set port.
     *
     * @param  int port
     * @return self
     */
    final public function setPort($port) {
        if ($port = intval($port)) {
            $this->port = $port;
        }

        return $this;
    }

    /**
     * Set user.
     *
     * @param  string $user
     * @return self
     */
    final public function setUser($user) {
        if ($user = trim($user)) {
            $this->user = $user;
        }

        return $this;
    }

    /**
     * Set pass.
     *
     * @param  string $pass
     * @return self
     */
    final public function setPass($pass) {
        if ($pass = trim($pass)) {
            $this->pass = $pass;
        }

        return $this;
    }

    /**
     * Set path.
     *
     * @param  string $path
     * @return self
     */
    final public function setPath($path) {
        if ($path = trim($path)) {
            $this->path = $path;
        }

        return $this;
    }

    /**
     * Set query.
     *
     * @param  string $query
     * @return self
     */
    final public function setQuery($query) {
        if ($query = trim($query)) {
            $this->query = $query;
        }

        return $this;
    }

    /**
     * Set fragment.
     *
     * @param  string $fragment
     * @return self
     */
    final public function setFragment($fragment) {
        if ($fragment = trim($fragment)) {
            $this->fragment = $fragment;
        }

        return $this;
    }

    /**
     * Get source.
     *
     * @return mixed
     */
    final function getSource() {
        return $this->source;
    }

    /**
     * Get scheme.
     *
     * @return string
     */
    final public function getScheme() {
        return $this->scheme;
    }

    /**
     * Get host.
     *
     * @return string
     */
    final public function getHost() {
        return $this->host;
    }

    /**
     * Get port.
     *
     * @return int
     */
    final public function getPort() {
        return $this->port;
    }

    /**
     * Get user.
     *
     * @return string
     */
    final public function getUser() {
        return $this->user;
    }

    /**
     * Get pass.
     *
     * @return string
     */
    final public function getPass() {
        return $this->pass;
    }

    /**
     * Get path.
     *
     * @return string
     */
    final public function getPath() {
        return $this->path;
    }

    /**
     * Get query.
     *
     * @return string
     */
    final public function getQuery() {
        return $this->query;
    }

    /**
     * Get fragment.
     *
     * @return string
     */
    final public function getFragment() {
        return $this->fragment;
    }

    /**
     * Get URI as array.
     *
     * @param  array $exclude
     * @return array
     */
    final public function toArray(array $exclude = []) {
        $return = [];
        foreach (['scheme', 'host', 'port', 'user',
                  'pass', 'path', 'query', 'fragment'] as $key) {
            if (!in_array($key, $exclude)) {
                $return[$key] = $this->$key;
            }
        }

        return $return;
    }

    /**
     * Get URI as string.
     *
     * @param  array $exclude
     * @return string
     */
    final public function toString(array $exclude = null) {
        $array  = $this->toArray($exclude);
        $return = '';

        isset($array['scheme']) &&
            $return .= $array['scheme'] . '://';
        if (isset($array['user']) || isset($array['pass'])) {
            isset($array['user']) &&
                $return .= $array['user'];
            isset($array['pass']) &&
                $return .= ':' . $array['pass'];
            $return .= '@';
        }
        isset($array['host']) &&
            $return .= $array['host'];
        isset($array['port']) &&
            $return .= ':' . $array['port'];
        isset($array['path']) &&
            $return .= $array['path'];
        isset($array['query']) &&
            $return .= '?' . $array['query'];
        isset($array['fragment']) &&
            $return .= '#' . $array['fragment'];

        return $return;
    }
}

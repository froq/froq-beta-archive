<?php
/**
 * Copyright (c) 2016 Kerem Güneş
 *    <k-gun@mail.com>
 *
 * GNU General Public License v3.0
 *    <http://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Application\Http\Uri;

/**
 * @package    Application
 * @subpackage Application\Http\Uri
 * @object     Application\Http\Uri\Uri
 * @author     Kerem Güneş <k-gun@mail.com>
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
    * Constructor.
    *
    * @param mixed $source
    */
   final public function __construct($source = null)
   {
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

         // segments
         if ($this->path != '') {
            $app = app();
            $this->segments = UriPath::generateSegments($this->path, $app->root);
         }
      }
   }

   /**
    * Get URI as string.
    *
    * @return string
    */
   final public function __toString(): string
   {
      return $this->toString();
   }

   /**
    * Set scheme.
    *
    * @param  string $scheme
    * @return self
    */
   final public function setScheme($scheme): self
   {
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
   final public function setHost($host): self
   {
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
   final public function setPort($port): self
   {
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
   final public function setUser($user): self
   {
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
   final public function setPass($pass): self
   {
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
   final public function setPath($path): self
   {
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
   final public function setQuery($query): self
   {
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
   final public function setFragment($fragment): self
   {
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
    * @return string|null
    */
   final public function getScheme()
   {
      return $this->scheme;
   }

   /**
    * Get host.
    *
    * @return string|null
    */
   final public function getHost()
   {
      return $this->host;
   }

   /**
    * Get port.
    *
    * @return int|null
    */
   final public function getPort()
   {
      return $this->port;
   }

   /**
    * Get user.
    *
    * @return string|null
    */
   final public function getUser()
   {
      return $this->user;
   }

   /**
    * Get pass.
    *
    * @return string|null
    */
   final public function getPass()
   {
      return $this->pass;
   }

   /**
    * Get path.
    *
    * @return string|null
    */
   final public function getPath()
   {
      return $this->path;
   }

   /**
    * Get query.
    *
    * @return string|null
    */
   final public function getQuery()
   {
      return $this->query;
   }

   /**
    * Get fragment.
    *
    * @return string|null
    */
   final public function getFragment()
   {
      return $this->fragment;
   }

   /**
    * Get URI as array.
    *
    * @param  array $exclude
    * @return array
    */
   final public function toArray(array $exclude = []): array
   {
      $return = [];
      foreach (['scheme', 'host', 'port', 'user',
                'pass', 'path', 'query', 'fragment'] as $key) {
         if (!in_array($key, $exclude)) {
            $return[$key] = $this->{$key};
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
   final public function toString(array $exclude = null): string
   {
      $array = $this->toArray($exclude);
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

   /**
    * Get segment value.
    *
    * @param  int $i
    * @return mixed|null
    */
   final public function segment($i, $default = null)
   {
      if (isset($this->segments[$i])) {
         return $this->segments[$i];
      }

      return $default;
   }

   /**
    * Get segments.
    *
    * @return array
    */
   final public function segments(): array
   {
      return $this->segments;
   }
}

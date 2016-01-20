<?php declare(strict_types=1);
namespace Application\Util;

use Application\Util\Traits\{SingleTrait, GetterTrait};
use Application\Encryption\Salt;

/**
 * @package    Application
 * @subpackage Application\Util
 * @object     Application\Util\Session
 * @uses       Application\Util\Traits\{SingleTrait, GetterTrait}
 *             Application\Encryption\Salt
 * @author     Kerem! <qeremy@gmail>
 */
final class Session
{
    /**
     * Singleton.
     * @var Application\Util\Traits\SingleTrait
     */
    use SingleTrait;

    /**
     * Property.
     * @var Application\Util\Traits\GetterTrait
     */
    use GetterTrait;

    /**
     * Session ID.
     * @var string
     */
    private $id;

    /**
     * Session name.
     * @var string
     */
    private $name;

    /**
     * Session states.
     * @var bool, bool
     */
    private $isStarted   = false,
            $isDestroyed = false;

    /**
     * Session options.
     * @var array
     */
    private $options = [
        'name'             => 'SID',
        'length'           => 32,
        'length_default'   => 32,
        'length_available' => [32, 40, 64, 128], // SID lengths
    ];

    /**
     * Object constructor.
     */
    final private function __construct(array $options = [])
    {
        // merge options
        $this->options = array_merge($this->options, $options);

        // store name
        $this->name = $this->options['name'];

        // check length
        if (!in_array($this->options['length'], $this->options['length_available'])) {
            $this->options['length'] = $this->options['length_default'];
        }

        // session is active?
        if (!$this->isStarted || session_status() != PHP_SESSION_ACTIVE) {
            // use hex hash
            // ini_set('session.session.hash_function', 1);
            // ini_set('session.hash_bits_per_character', 4);

            // if save path provided @tmp?
            if (is_local() && isset($this->options['save_path'])) {
                if (!is_dir($this->options['save_path'])) {
                    mkdir($this->options['save_path'], 0777, true);
                    chmod($this->options['save_path'], 0777);
                }
                ini_set('session.save_path', $this->options['save_path']);
                // set perms
                foreach (glob($this->options['save_path'] .'/*') as $file) {
                    chmod($file, 0777);
                }
            }

            // set defaults
            session_set_cookie_params(
                (int)    $this->options['lifetime'],
                (string) $this->options['path'],
                (string) $this->options['domain'],
                (bool)   $this->options['secure'],
                (bool)   $this->options['httponly']
            );

            // set session name
            session_name($this->name);

            // reset session data and start session
            $this->reset();
            $this->start();
        }
    }

    /**
     * End the current session and store session data.
     *
     * @return void
     */
    final public function __destruct()
    {
        session_register_shutdown();
    }

    /**
     * Set a session var.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    final public function __set(string $key, $value)
    {
        if (!isset($_SESSION[$this->name])) {
            // stop writing first
            session_abort();

            throw new \RuntimeException(sprintf(
                'Session not started yet, call first `%s::start()` or use isset() first!',
                    __class__
            ));
        }

        $_SESSION[$this->name][$key] = $value;
    }

    /**
     * Get a session var.
     *
     * @param  string $key
     * @return mixed
     */
    final public function __get(string $key)
    {
        if (!isset($_SESSION[$this->name])) {
            throw new \RuntimeException(sprintf(
                'Session not started yet, call first `%s::start()` or use isset() first!',
                    __class__
            ));
        }

        return array_key_exists($key, $_SESSION[$this->name])
            ? $_SESSION[$this->name][$key] : null;
    }

    /**
     * Check a session var.
     *
     * @param  string $key
     * @return bool
     */
    final public function __isset(string $key): bool
    {
        return isset($_SESSION[$this->name])
            && array_key_exists($key, $_SESSION[$this->name]);
    }

    /**
     * Remove a session var.
     *
     * @param  string $key
     * @return void
     */
    final public function __unset(string $key)
    {
        unset($_SESSION[$this->name][$key]);
    }

    /**
     * Set a session var.
     *
     * @param  string $key
     * @return void
     */
    final public function set(string $key, $value)
    {
        $this->__set($key, $value);
    }

    /**
     * Get a session var or default value.
     *
     * @param  string $key
     * @param  mixed  $valueDefault
     * @return mixed
     */
    final public function get(string $key, $valueDefault = null)
    {
        return (null !== ($value = $this->__get($key)))
            ? $value : $valueDefault;
    }

    /**
     * Remove a session var.
     *
     * @param  string $key
     * @return void
     */
    final public function remove(string $key)
    {
        $this->__unset($key);
    }

    /**
     * Get session id.
     *
     * @return string
     */
    final public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get session name.
     *
     * @return string
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get options.
     *
     * @return array
     */
    final public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Check wheter session is started.
     *
     * @return bool
     */
    final public function isStarted(): \Foo
    {
        return $this->isStarted;
    }

    /**
     * Check wheter session is destroyed.
     *
     * @return bool
     */
    final public function isDestroyed(): bool
    {
        return $this->isDestroyed;
    }

    /**
     * Check SID is valid.
     *
     * @param  string $id
     * @return bool
     */
    final public function isValidId($id = null): bool
    {
        // see self.generateId()
        return (bool) preg_match('~^[A-F0-9]{'. $this->options['length'] .'}$~', $id);
    }

    /**
     * Check SID file is valid.
     *
     * @param  string $id
     * @return bool
     */
    final public function isValidFile($id): bool
    {
        return is_file(sprintf('%s/sess_%s', ini_get('session.save_path'), $id));
    }

    /**
     * Start session and set session id.
     *
     * @return bool
     */
    final public function start(): bool
    {
        // app
        $app = app();

        // check started?
        if ($this->isStarted) {
            return true;
        }

        // check headers
        if (headers_sent($file, $line)) {
            throw new \RuntimeException(sprintf(
                'Call `%s()` before outputs have been sent. [output location: `%s:%s`]',
                    __method__, $file, $line
            ));
        }

        // set/check id
        $id = session_id();
        if ($this->isValidId($id)) {
            $this->id = $id;
        } else {
            $id = $app->request->cookies->get($this->name, '');
            // hard and hard..
            if ($this->isValidId($id) && $this->isValidFile($id)) {
                $this->id = $id;
            } else {
                // generate new one
                $this->id = $this->generateId();
            }
        }

        /**
         * Note: When using session cookies, specifying an id for session_id() will always send a new
         * cookie when session_start() is called, regardless if the current session id is identical to
         * the one being set. */
        // set session id
        session_id($this->id);

        // start session
        $this->isStarted = session_start();
        if (!$this->isStarted) {
            // stop writing first
            session_write_close();

            throw new \RuntimeException(sprintf('Session start is failed in `%s()`', __method__));
        }

        // init subpart
        if (!isset($_SESSION[$this->name])) {
            $_SESSION[$this->name] = array();
        }

        return $this->isStarted;
    }

    /**
     * Destroy session.
     *
     * @return bool
     */
    final public function destroy(bool $deleteCookie = true): bool
    {
        // check destroy before?
        if (!$this->isDestroyed) {
            $this->isDestroyed = session_destroy();
            // reset session data
            if ($this->isDestroyed) {
                $this->reset();
            }
            // delete cookie?
            if ($deleteCookie) {
                $this->deleteCookie();
            }
        }

        // remove session id
        $this->id = null;

        return $this->isDestroyed;
    }

    /**
     * Delete session cookie.
     *
     * @return void
     */
    final public function deleteCookie()
    {
        if (isset($_COOKIE[$this->name])) {
            $cookieParams = session_get_cookie_params();
            setcookie($this->name, null, 322790400,
                $cookieParams['path'],
                $cookieParams['domain'],
                $cookieParams['secure']
            );
        }
    }

    /**
     * Generate id.
     *
     * @return string
     */
    final function generateId(): string
    {
        // get a random salt
        $id = Salt::generate(Salt::TYPE_URANDOM, Salt::LENGTH, false);

        // encode by length
        switch ($this->options['length']) {
            case  32: $id = hash('md5', $id); break;
            case  40: $id = hash('sha1', $id); break;
            case  64: $id = hash('sha256', $id); break;
            case 128: $id = hash('sha512', $id); break;
        }

        // return upper'ed
        return strtoupper($id);
    }

    /**
     * Regenerate session id
     *
     * @param  bool $deleteOldSession
     * @return bool
     */
    final public function regenerateId(bool $deleteOldSession = true): bool
    {
        // check headers sent?
        if (headers_sent($file, $line)) {
            throw new Exception(sprintf(
                'Call to `%s()` after outputs have been sent. [output location is `%s:%s`]',
                    __method__, $file, $line
            ));
        }
        // regenerate
        $return = session_regenerate_id($deleteOldSession);

        // store session id
        $this->id = session_id($this->generateId());

        return $return;
    }

    /**
     * Flash messages.
     *
     * @param  string $message
     * @return mixed
     */
    final public function flash(string $message = null)
    {
        // get flash message
        if ($message === null) {
            $message = $this->get('@@@flash@@@');
            $this->remove('@@@flash@@@');

            return $message;
        }

        return $this->set('@@@flash@@@', $message);
    }

    /**
     * Reset session global.
     *
     * @return void
     */
    final private function reset()
    {
        $_SESSION[$this->name] = array();
    }

    /**
     * Get session sub-array.
     *
     * @return array
     */
    final public function toArray(): array
    {
        $array = array();
        if (isset($_SESSION[$this->name])) {
            $array = to_array($_SESSION[$this->name], true);
        }

        return $array;
    }
}

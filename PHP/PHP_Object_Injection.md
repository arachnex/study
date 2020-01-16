# PHP Object Injection

## PHP Magic Methods
PHP 클래스에는 Magic Functions 라고 불리는 특별한 메소드들이 있다. 이들은 이름이 `__` 로 시작한다는 특징을 가지고 있으며, `__construct()`, `__destruct()`, `__toString()`, `__sleep()`, `__wakeup()` 등이 있다.

- `__construct()` : 클래스의 생성자 함수로, 객체가 생성될 때 호출된다.
- `__destruct()` : 클래스의 소멸자 함수로, 객체가 소멸될 때 호출된다.
- `__toString()` : 객체가 문자열로 사용될 때 호출된다. 

## Serialization
`serialize()` 는 값의 저장 가능한 표현을 생성하는 함수이다. 어디에나 저장할 수 있는 값의 byte-stream 표현을 포함한 문자열을 반환한다. PHP 값을 유형과 구조의 변형 없이 저장하거나 전달하는 데 유용하다.

객체를 직렬화할 때, PHP는 직렬화하기 전에 멤버 함수인 `__sleep()` 함수를 호출하려고 시도한다. 이는 객체가 마지막 정리 등을 수행할 수 있도록 하기 위해서이다.

### Description
- String
    - `s:size:value;`
    - String 값은 항상 큰따옴표로 감싸주어야 한다.
- Integer
    - `i:value;`
- Boolean
    - `b:value;`
    - `value` 는 `true` or `false` 대신, `1` or `0` 을 사용한다.
- Null
    - `N;`
- Array
    - `a:size:{key definition;value definition;(repeated per element)}`
    - `key` 나 `value` 에는 자료형의 표현식이 들어간다.
    - 요소의 수만큼 중괄호 안에 key-value 의 표현식이 반복된다.
- Object
    - `O:strlen(object name):object name:object size:{s:strlen(property name):property name:property definition;(repeated per property)}`
    - `property` 의 값에는 자료형의 표현식이 들어간다.
    - 프로퍼티의 수만큼 중괄호 안에 위의 구조가 반복된다.

## Unserialization
`unserialize()` 는 직렬화되어 저장된 표현을 다시 PHP 값으로 되돌려주는 함수이다. 역직렬화를 통해 객체를 복원하면 PHP는 멤버 함수인 `__wakeup()` 함수를 호출한다.

역직렬화하는 과정에서 객체가 인스턴스화 및 자동으로 로드되기 때문에 코드가 실행될 수 있다. 사용자의 입력을 `unserialize()` 에 전달하는 경우, RCE 등의 보안 문제가 발생할 수 있다.

## PHP Object Injection
**PHP Object Injection**은 사용자의 입력이 `unserialize()` 함수에 전달되기 전에 적절히 필터링되지 않은 경우에 발생하는 취약점이다. 상황에 따라 Code Injection, SQL Injection, Path Traversal, Application DoS와 같은 여러 종류의 악의적인 공격을 수행할 수 있다.

아래의 코드는 악용 가능한 `__wakeup()` 메소드가 있는 PHP 클래스이다. 내용은 [owasp.org](https://www.owasp.org/index.php/PHP_Object_Injection)를 참조하였다.

```php
class Example2
{
   private $hook;

   function __construct()
   {
      // some PHP code...
   }

   function __wakeup()
   {
      if (isset($this->hook)) eval($this->hook);
   }
}

// some PHP code...

$user_data = unserialize($_COOKIE['data']);

// some PHP code...
```

코드를 보면 쿠키의 `data` 파라미터 값을 `unserialize` 한다. 만약 `Example2` 클래스 객체를 `unserialize` 한다면 `__wakeup()` 메소드가 호출되고, `eval()` 함수에 의해 `$hook` 변수의 값이 설정될 것이다. 여기서 아래와 같은 HTTP 요청을 보내 Code Injection 공격을 수행할 수 있다.

```http
GET /vuln.php HTTP/1.0
Host: testsite.com
Cookie: data=O%3A8%3A%22Example2%22%3A1%3A%7Bs%3A14%3A%22%00Example2%00hook%22%3Bs%3A10%3A%22phpinfo%28%29%3B%22%3B%7D
Connection: close
```

결과적으로 다음과 같은 코드가 생성된다.

```php
class Example2
{
   private $hook = "phpinfo();";
}

print urlencode(serialize(new Example2));
```

## References
- https://www.php.net/manual/en/function.serialize.php
- https://www.php.net/manual/en/function.unserialize.php
- https://www.owasp.org/index.php/PHP_Object_Injection
- https://securitycafe.ro/2015/01/05/understanding-php-object-injection/
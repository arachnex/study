# Cross-Site Scripting

**XSS**(Cross-Site Scripting)는 악의적인 스크립트를 웹 사이트에 삽입하는 인젝션 공격의 한 종류이다. 일반적으로 공격자가 웹 애플리케이션을 사용하여 브라우저 측 스크립트 형태로 악성 코드를 다른 최종 사용자에게 전달할 때 발생한다. XSS 공격이 성공할 수 있는 결함은 매우 광범위하며, 웹 애플리케이션이 사용자의 입력을 유효성 검사나 인코딩 없이 출력하는 모든 곳에서 발생한다.

공격자는 XSS를 사용하여 악의적인 스크립트를 의심없는 사용자에게 보낼 수 있다. 최종 사용자의 브라우저는 해당 스크립트를 신뢰할 수 없다는 것을 알지 못하기 때문에, 신뢰할 수 있는 곳에서 전달되었다고 판단하여 그 스크립트를 실행할 것이다. 악성 스크립트는 브라우저가 보유하고 해당 사이트에서 사용하는 쿠키나 세션 토큰 또는 다른 민감한 정보에 접근할 수 있고, HTML 페이지의 내용을 수정할 수 있다.

## Types of XSS
### Reflected XSS
**Reflected XSS**는 오류 메시지나 검색 결과 또는 요청의 일부로 서버에 전송된 입력을 포함하는 응답과 같이 삽입된 스크립트가 웹 서버에 반영되는 공격이다. 애플리케이션이 HTTP 요청으로 데이터를 수신할 때 발생하며, 전자 메일 메시지 또는 다른 웹 사이트와 같은 경로를 통해 사용자에게 전달된다. 사용자가 악의적인 링크를 클릭하거나 조작된 양식을 제출하였을 때, 삽입된 코드는 취약한 웹 사이트로 이동하여 사용자의 브라우저에 대한 공격을 반영한다. 브라우저는 코드가 신뢰할 수 있는 서버에서 왔다고 판단하기 때문에 실행하게 된다.

다음은 Reflected XSS의 간단한 예시이다.

```
http://www.example.com/index.php?message=blah+blah+blah
```
```html
<p>Message: blah blah blah</p>
```

사용자가 입력한 값이 URL의 `message` 파라미터를 통해 요청이 전송된다. 애플리케이션이 해당 데이터에 대한 아무런 처리를 수행하지 않고 출력하는 경우, 공격자는 다음과 같이 쉽게 공격할 수 있다.

```
http://www.example.com/index.php?message=<script>alert(1);</script>
```
```html
<p>Message: <script>alert(1);</script></p>
```

### Stored XSS
**Stored XSS**는 삽입된 스크립트가 데이터베이스 또는 게시물, 댓글, 방문자 기록 등 대상 서버에 영구적으로 저장되는 공격이다. 그러면 사용자가 저장된 정보를 요청할 때 서버에서 악의적인 스크립트를 검색하게 된다.

다음은 Stored XSS의 간단한 예시이다. 게시판 애플리케이션과 같은 경우에 사용자의 입력을 저장하여 다른 사람에게 출력한다.

```html
<p>Hello, This is my message!</p>
```

애플리케이션이 사용자의 입력에 대해 필터링이나 다른 데이터 처리를 수행하지 않으면 공격자는 다른 사용자를 공격하는 스크립트를 쉽게 삽입할 수 있다.

```html
<p><script>alert(1);</script></p>
```

### DOM-based XSS
**DOM-based XSS**는 애플리케이션이 신뢰할 수 없는 소스의 데이터를 안전하지 않은 방식으로 처리하는 클라이언트 측 자바스크립트가 포함된 경우에 발생한다. 일반적으로 DOM 영역에 데이터를 다시 쓰는 경우에 발생한다.

다음은 DOM-based XSS의 예시이다. 애플리케이션은 자바스크립트를 사용하여 입력 필드에서 값을 읽고 해당 값을 HTML 코드 내의 요소로 출력한다.

```js
var search = document.getElementById('search').value;
var results = document.getElementById('results');
results.innerHTML = 'You searched for: ' + search;
```

공격자가 입력 필드의 값을 제어할 수 있다면 악의적인 스크립트를 실행시키는 값을 다음과 같이 쉽게 구성할 수 있다.

```html
You searched for: <img src='' onerror='<script>alert(1);</script>'>
```

## References
- https://owasp.org/www-community/attacks/xss/
- https://portswigger.net/web-security/cross-site-scripting
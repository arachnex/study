# Server-Side Request Forgery

**SSRF**(서버 측 요청 위조)는 서버 측 애플리케이션이 공격자가 선택한 임의의 도메인으로 HTTP 요청을 하도록 유도하는 웹 보안 취약점이다. 공격자는 서버의 기능을 악용하여 서버 내부의 리소스를 읽거나 수정할 수 있다.

SSRF 공격이 성공하면 취약한 애플리케이션 자체 또는 애플리케이션과 통신할 수 있는 다른 백엔드 시스템에서 조직 내의 데이터에 대한 무단 작업 또는 접근이 발생할 수 있다. 경우에 따라 공격자는 SSRF 취약점을 통해 임의 명령 실행을 수행할 수 있다.

## Common SSRF Attacks
SSRF 공격은 종종 신뢰 관계를 악용하여 취약한 애플리케이션에 대한 공격을 점차 확대하고 무단 작업을 수행한다. 이러한 신뢰 관계는 서버 자체 또는 같은 조직 내의 다른 백엔드 시스템과 관련하여 존재할 수 있다.

### Server Itself
서버 자체에 대한 SSRF 공격에서 공격자는 루프백 네트워크 인터페이스를 통해 애플리케이션을 호스팅하는 서버에 HTTP 요청을 다시 보내도록 애플리케이션을 유도한다. 

예를 들어 사용자가 특정 상점에 재고가 있는지를 확인할 수 있는 쇼핑 애플리케이션이 있다고 할 때, 재고 정보를 제공하기 위해서는 애플리케이션이 제품과 상점에 따라 여러 백엔드 REST API에 질의해야 한다. 이 기능은 프론트엔드 HTTP 요청을 통해 관련 백엔드 API 엔드포인트로 URL을 전달하여 구현된다. 따라서 사용자가 상품에 대한 재고 상태를 볼 때 브라우저는 다음과 같이 요청한다.

```http
POST /product/stock HTTP/1.0
Content-Type: application/x-www-form-urlencoded
Content-Length: 118

stockApi=http://stock.weliketoshop.net:8080/product/stock/check%3FproductId%3D6%26storeId%3D1
```

위의 요청으로 인해 서버는 지정된 URL에 요청을 하고 재고 상태를 검색한 후에 결과를 사용자에게 반환한다. 공격자는 해당 요청을 수정하여 서버 자체의 로컬 URL로 지정할 수 있다.

```http
POST /product/stock HTTP/1.0
Content-Type: application/x-www-form-urlencoded
Content-Length: 118

stockApi=http://localhost/admin
```

그러면 서버는 `/admin`의 내용을 가져와서 사용자에게 반환한다. 공격자는 해당 URL에 직접 접근할 수 있지만, 인증된 사용자만이 관리 기능을 사용할 수 있다. 그러나 요청이 로컬 시스템 자체에서 전송되면 일반 접근 제어는 무시된다. 요청이 신뢰할 수 있는 위치에서 시작된 것으로 보이기 때문에 애플리케이션은 관리 기능에 대한 전체 접근 권한을 부여한다.

### Other Back-end Systems
서버 측 요청 위조와 함께 자주 발생하는 또 다른 유형의 신뢰 관계는 애플리케이션 서버가 사용자가 직접 연결할 수 없는 다른 백엔드 시스템과 상호 작용할 수 있는 경우이다. 이러한 시스템에는 라우팅할 수 없는 전용 IP 주소가 있는 경우가 많다. 백엔드 시스템은 일반적으로 네트워크 토폴로지에 의해 보호되기 때문에 보안 상태가 약한 경우가 많다. 대부분의 내부 백엔드 시스템은 시스템과 상호 작용할 수 있는 사용자가 인증 없이 접근할 수 있는 민감한 기능을 포함하고 있다.

백엔드 URL `https://192.168.0.68/admin`에 관리 인터페이스가 있다고 가정할 때, 공격자는 SSRF 취약점을 통해 다음 요청을 제출하여 관리 인터페이스에 접근할 수 있다.

```http
POST /product/stock HTTP/1.0
Content-Type: application/x-www-form-urlencoded
Content-Length: 118

stockApi=http://192.168.0.68/admin
```

## References
- https://portswigger.net/web-security/ssrf
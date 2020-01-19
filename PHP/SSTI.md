# Server-Side Template Injection

## Template Injection
템플릿 엔진은 HTML 코드의 동적 생성을 더 쉽게 관리할 수 있는 방법을 제공한다. Server-Side Template을 사용하면 정적 HTML처럼 서버 측에서 동적 HTML 페이지를 생성할 수 있다는 장점이 있다. 그런데 만약 사용자의 입력이 안전하지 않은 방식으로 템플릿 문자열에 삽입될 수 있다면 사용자가 템플릿 표현식을 실행시킬 수 있다. 마치 `eval()` 함수에 검증되지 않은 값을 전달하는 것과 비슷하기 때문에 시스템 명령어를 삽입할 수 있다면 RCE로 이어질 수 있다. 이러한 공격을 **Template Injection** 이라고 한다.

**Template Injection**은 주로 위키, 블로그, 마케팅 애플리케이션, 콘텐츠 관리 시스템 등에서 개발자의 실수 또는 풍부한 기능을 제공하기 위해 의도적으로 템플릿을 노출하는 경우에 발생할 수 있다. 의도적인 템플릿 삽입은 많은 템플릿 엔진이 이러한 명시적인 목적으로 샌드박스 모드를 제공하는 일반적인 사용 사례이다. XSS와 달리 웹 서버의 내부를 직접 공격하고, RCE를 통해 취약한 애플리케이션을 잠재적인 중심점으로 전환하는 데 사용될 수 있다.

## Introduction
웹 애플리케이션은 웹 페이지와 이메일에 동적 콘텐츠를 내장하기 위해 종종 **Twig**이나 **FreeMarker**와 같은 템플릿 시스템을 사용한다. 다음은 대량의 이메일을 보내기 위한 Twig 템플릿의 예시이다.

> $output = $twig->render("**Dear {first_name}**,", array("first_name" => $user.first_name) );

`{first_name}`에 이름을 전달하면 각각의 사용자에 대한 이메일 형식이 생성될 것이다. 하지만 이 부분에 사용자가 이메일을 정의할 수 있도록 하는 경우 문제가 발생한다.

> $output = $twig->render(**$_GET['custom_email']**,  array("first_name" => $user.first_name) );

여기서는 사용자가 템플릿에 전달된 값이 아닌 GET 방식의 파라미터를 통해 템플릿 자체의 내용을 제어한다. `custom_email` 파라미터에 `{{7*7}}` 이라는 값을 전달했을 때 계산식의 결과가 출력된다면 취약한 템플릿이므로, 계산식 대신 환경 변수나 시스템 명령어 등을 입력하여 공격을 시도할 수 있다.

## Identification
취약점을 발견하였다면 시스템이 사용하고 있는 템플릿 엔진을 식별해야 한다. 잘못된 구문을 입력했을 때 템플릿 엔진이 결과 오류 메시지를 출력한다면 쉽게 식별이 가능하다. 그러나 불가능한 경우에는 아래의 의사 결정 트리를 이용하여 공략을 시도한다.

![Identify](https://portswigger.net/cms/images/migration/blog/screen-shot-2015-07-20-at-09-21-56.png)

## References
- https://portswigger.net/research/server-side-template-injection
- https://www.netsparker.com/blog/web-security/server-side-template-injection/
- https://github.com/swisskyrepo/PayloadsAllTheThings/tree/master/Server%20Side%20Template%20Injection
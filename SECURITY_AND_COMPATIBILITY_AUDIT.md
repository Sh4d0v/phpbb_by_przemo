# Audyt bezpieczeństwa i zgodności — lokalny patch 1.13.0

> Ten dokument opisuje roboczy audyt historycznego kodu phpBB 2 zmodyfikowanego przez Przema. To nie jest certyfikat bezpieczeństwa ani dowód, że aplikacja jest gotowa do publicznego wdrożenia. Patch pozostaje lokalny i nie został opublikowany.

## Jak działa aplikacja

1. Pliki wejściowe forum definiują `IN_PHPBB`, ustawiają katalog główny i dołączają `extension.inc` oraz `common.php`.
2. `common.php` ładuje ustawienia z `config.php`, przygotowuje stare aliasy zmiennych HTTP, inicjalizuje bazę i sesję oraz pobiera konfigurację forum.
3. `includes/db.php` wybiera sterownik `sql_db`. Sterownik wykonuje zapytania SQL, a pozostałe moduły (publikowanie postów, logowanie, panel administratora, załączniki, statystyki) korzystają z metod `$db->sql_*`.
4. Szablony w `templates/`, tłumaczenia w `language/` i moduły rozszerzeń generują widoki. Uploady i dane forum są przechowywane zarówno w bazie, jak i katalogach `files/`, `images/`, `cache/`.
5. `install.php` tworzy schemat, dane początkowe, konto administratora i `config.php`. `scripts/update.php` uruchamia migracje SQL; `dbloader/` obsługuje import/eksport i naprawę konfiguracji.

## Zmiany w tym patchu

- Dodano `includes/legacy_compat.php` i załadowano go z `common.php` oraz `install.php`. Zapewnia brakujące `each()` i stare funkcje magic-quotes potrzebne przez kod sprzed PHP 7.
- Zmieniono składnię indeksowania stringów/tablic usuniętą w PHP 8 oraz usunięto powielony parametr w deklaracji galerii awatarów.
- Stare ustawienia `mysql` i `mysql4` są kierowane do MySQLi. Konstruktor sterownika MySQLi działa jako `__construct`; kod wyłącza domyślne wyjątki MySQLi z PHP 8.1+, aby zachować dotychczasowy kontrakt zwracania `false`.
- Połączenie MySQLi zachowuje kodowanie `latin2` wymagane przez historyczne dane. Usuwa `ONLY_FULL_GROUP_BY` wyłącznie dla bieżącej sesji SQL, bo istnieją zapytania z dawną, niezgodną z tym trybem semantyką `GROUP BY`; pozostałe tryby SQL zostają zachowane.
- Instalator wymaga tokenu CSRF, ogranicza sterownik do MySQLi, waliduje prefiks tabel, nazwę hosta, port i ścieżkę forum oraz koduje wartości `config.php` przez `var_export()`. Plik konfiguracyjny otrzymuje restrykcyjne prawa dostępu.
- Nowe hasła używają `password_hash()`. Logowanie nadal rozpoznaje stare hashe phpass i MD5; po poprawnym logowaniu hashe są przenoszone do aktualnego algorytmu. Schemat i aktualizator 1.13.0 poszerzają pola hasła do 255 znaków.
- Nazwy zapisanych awatarów są losowane przez `random_bytes()`, a prawa pliku nie są już ustawiane na `0777`.
- Reguły Apache blokują bezpośredni dostęp do `config.php`, plików SQL i źródeł `.inc.php`, a katalog uploadów blokuje uruchamianie PHP/PHTML/PHAR.

## Istotne ryzyka pozostałe do usunięcia

### Wysokie — ręcznie składane zapytania SQL

Warstwa dostępu do bazy przyjmuje zwykłe teksty SQL, a w setkach miejsc aplikacji zapytania są składane przez konkatenację danych. Ten patch nie przepisuje całego forum na parametryzowane zapytania. Należy osobno prześledzić przepływ danych z `$_GET`, `$_POST`, cookies i importowanych plików do SQL; obecne `addslashes()` i ręczne `str_replace()` nie są wystarczającą granicą bezpieczeństwa.

### Wysokie — XSS i HTML w szablonach

Wiele historycznych ekranów renderuje wartości z bazy, ustawień forum, komunikatów błędów lub importów bez jednolitego kodowania kontekstowego. Zmiana wszystkich widoków wymaga przeglądu kontekstu HTML, atrybutów, URL i JavaScript; nie została wykonana w tym patchu.

### Wysokie — obsługa zdalnych avatarów i plików

Kod obsługi avatarów pobiera wskazany zdalny URL przez własne gniazdo HTTP. Wymaga przeglądu pod kątem SSRF (adresy prywatne/loopback, DNS rebinding, przekierowania, limit czasu/rozmiaru, błędne nagłówki). Zmieniono nazwę i prawa zapisywanego pliku, ale nie przebudowano pobierania. Załączniki i inne uploady też wymagają niezależnego sprawdzenia typu rzeczywistej zawartości, ścieżek, limitów i bezpiecznego serwowania.

### Wysokie — sesje i cookies forum

Forum nadal używa własnego mechanizmu sesji i cookies sprzed współczesnych praktyk, w tym identyfikatorów opartych na `md5(uniqid(...))` i cookies, do których kilka ścieżek ustawia hashe MD5. Nie wprowadzono globalnej rotacji sesji, `HttpOnly`/`SameSite` dla wszystkich cookies, pełnego CSRF-tokenu dla wszystkich mutujących formularzy ani nowego formatu tokenów.

### Wysokie — przejściowe uruchomienie `install.php`

Po instalacji plik instalatora należy usunąć lub zablokować na serwerze. Przy utracie `config.php` i dostępnej bazie instalator nadal wymaga ręcznego, destrukcyjnego wyboru usuwania istniejących tabel. Nie dodano trwałej blokady instalatora poza poprawnie zapisanym `config.php`.

### Średnie — zgodność z MySQL 8.x/9.x

Zachowanie sesyjnie permissive dla `ONLY_FULL_GROUP_BY` umożliwia część starych zapytań, ale nie naprawia ich semantyki. Tryby strict pozostają aktywne i mogą ujawnić błędne albo nadmiarowe dane wejściowe. Nie wykonano migracji rzeczywistej bazy ani przeglądu wszystkich zapytań pod kątem zarezerwowanych słów, typów, kolacji, `GROUP BY`, rozmiaru indeksów i zmian zachowania. Nie ma też gwarancji zgodności z MySQL 9.x.

### Wysokie — wsparcie projektu i środowiska

Zgodność składniowa z nowszym PHP nie oznacza bezpiecznej aplikacji. W katalogu są przestarzałe biblioteki, własny mechanizm autoryzacji oraz stare rozszerzenia. Serwer powinien być odizolowany do czasu pełnego audytu, testów funkcjonalnych i aktualizacji danych.

## Sprawdzenia wykonane lokalnie

- Repozytorium bazowe zawierało 288 plików PHP; po dodaniu warstwy zgodności patch obejmuje 289 plików PHP.
- Pierwszy przebieg `php -l` na dostępnej lokalnej wersji PHP 8.2.28 wskazał składnię indeksowania usuniętą w PHP 8 w kilku plikach oraz zduplikowany parametr. Zmieniono te miejsca.
- Drugi przebieg wskazał dwa dalsze indeksowania w `admin/admin_users.php` i `admin/admin_custom_fields.php`, które również poprawiono.
- Sprawdzono `git diff --check`; uwagi o końcach linii CRLF wynikają z `core.autocrlf`, nie z błędów składni.
- Nie podłączono bazy danych i nie uruchomiono instalacji, aktualizacji, logowania, uploadów ani pełnego forum w przeglądarce. Nie wykonano testów obciążeniowych, integracyjnych ani testu MySQL 8.4/9.x.

## Zalecana kolejność dalszych prac

1. Sprawdzić migrację 1.12.9 → 1.13.0 na kopii bazy, w tym odtworzenie z backupu i logowanie istniejącymi hasłami.
2. Utworzyć testowe środowisko PHP 8.5 + MySQL 8.4 LTS i przejść instalację, aktualizację, logowanie, rejestrację, publikowanie, załączniki i panel administracyjny.
3. Naprawić i przetestować przepływy SQL oraz XSS od wejścia do widoku; następnie przejrzeć SSRF/uploady, sesje, cookies i CSRF.
4. Dopiero po audycie zależności i konfiguracji serwera rozważyć migrację na produkcję. Nie wdrażać archiwalnego forum bez aktualnej kopii danych oraz planu wycofania.

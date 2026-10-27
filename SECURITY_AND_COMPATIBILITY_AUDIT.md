# Audyt bezpieczeństwa i zgodności — phpBB by Przemo 1.13.0

> Ten dokument opisuje statyczny audyt historycznego kodu phpBB 2 zmodyfikowanego przez Przema. Najnowszy zestaw poprawek 1.13.0 opublikowano w commicie [`db0c28a`](https://github.com/Sh4d0v/phpbb_by_przemo/commit/db0c28a). To nie jest certyfikat bezpieczeństwa ani dowód, że aplikacja jest gotowa do publicznego wdrożenia.

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
- Ostatni przegląd utwardził obsługę wielu ekranów ACP, zastąpił `eval()` w podstawianiu pól e-maili i zmienił wybrane `unserialize()` na wariant z `allowed_classes=false`.
- Import stylów odrzuca ścieżki archiwum wychodzące poza katalog szablonu, dowiązania symboliczne i archiwa przekraczające limity rozmiaru/liczby plików. Nazwa stylu w konfiguracji jest sprawdzana względem rzeczywistego katalogu `templates/`.
- Wspólny pobieracz awatarów, zdjęć profilu i feedu aktualizacji ogranicza pobieranie do publicznych adresów IPv4, HTTP na porcie 80, odpowiedzi 200, limitu danych i czasu. Obrazy przechodzą kontrolę formatu, a zapisywane uploady używają losowych nazw i praw bez globalnego zapisu.
- Narzędzia `check_files.php` i `dbloader/` mają ochronę CSRF dla obsługiwanych operacji zmieniających stan; importer bazy sprawdza też dostęp, prefiks tabel i ścieżkę pliku SQL.
- Odświeżono cały manifest `check_data.php`; wszystkie 450 sum kontrolnych i rozmiarów odpowiadają obecnym plikom repozytorium.

## Istotne ryzyka pozostałe do usunięcia

### Wysokie — ręcznie składane zapytania SQL

Warstwa dostępu do bazy przyjmuje zwykłe teksty SQL, a w setkach miejsc aplikacji zapytania są składane przez konkatenację danych. Ten patch nie przepisuje całego forum na parametryzowane zapytania. Należy osobno prześledzić przepływ danych z `$_GET`, `$_POST`, cookies i importowanych plików do SQL; obecne `addslashes()` i ręczne `str_replace()` nie są wystarczającą granicą bezpieczeństwa.

### Wysokie — XSS i HTML w szablonach

Wiele historycznych ekranów renderuje wartości z bazy, ustawień forum, komunikatów błędów lub importów bez jednolitego kodowania kontekstowego. Zmiana wszystkich widoków wymaga przeglądu kontekstu HTML, atrybutów, URL i JavaScript; nie została wykonana w tym patchu.

### Do dalszej weryfikacji — załączniki i inne operacje plikowe

Pobieranie zdalnych avatarów, zdjęć profilu i feedu stylów ograniczono do publicznych adresów IPv4 oraz HTTP na porcie 80; DNS jest rozwiązywany przed połączeniem, a rozmiar odpowiedzi, nagłówki i czas pobierania są ograniczone. Ogranicza to opisane wcześniej ryzyko SSRF, ale nie zastępuje testów na serwerze ani przeglądu wszystkich wyjść sieciowych. Załączniki i inne uploady nadal wymagają niezależnej weryfikacji typów, ścieżek, limitów i bezpiecznego serwowania.

### Wysokie — sesje i cookies forum

Forum nadal używa własnego mechanizmu sesji i cookies sprzed współczesnych praktyk, w tym identyfikatorów opartych na `md5(uniqid(...))` i cookies, do których kilka ścieżek ustawia hashe MD5. Ochrona CSRF została dodana do instalatora i wybranych narzędzi, ale nie obejmuje wszystkich formularzy zmieniających dane. Nie wprowadzono globalnej rotacji sesji, `HttpOnly`/`SameSite` dla wszystkich cookies ani nowego formatu tokenów.

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
- Po najnowszym zestawie poprawek wszystkie 289 śledzonych plików PHP przeszły `php -l` na dostępnej lokalnej wersji PHP 8.2.28.
- Zweryfikowano 450 wpisów sum kontrolnych i rozmiarów w `check_data.php`; nie stwierdzono rozbieżności. `git diff --check` nie wykazał błędów formatowania.
- Wykonano statyczne wyszukiwanie ryzykownych konstrukcji w repozytorium. Pozostałe `eval()` należą do historycznego kompilatora szablonów/BBCode i warunków modułów statystyk; ich wejścia są lokalnym kodem/konfiguracją, ale instalowanie niezaufanych stylów lub modułów pozostaje ryzykowne.
- W ramach tego lokalnego audytu nie podłączono bazy ani nie wykonywano testów integracyjnych lub penetracyjnych. Właściciel projektu potwierdza, że demo na hostingu z PHP 8.5 działa bez błędów. Lokalny lint wykonano na PHP 8.2.28; wersji MySQL używanej przez demo nie podano.

## Zalecana kolejność dalszych prac

1. Sprawdzić migrację 1.12.9 → 1.13.0 na kopii bazy, w tym odtworzenie z backupu i logowanie istniejącymi hasłami.
2. Na kopii danych sprawdzić instalację i migrację z wersją MySQL faktycznie używaną w docelowym środowisku; demo projektu jest już uruchomione na PHP 8.5.
3. Naprawić i przetestować przepływy SQL oraz XSS od wejścia do widoku; następnie przejrzeć pozostałe wyjścia sieciowe i uploady, sesje, cookies oraz globalne CSRF.
4. Dopiero po audycie zależności i konfiguracji serwera rozważyć migrację na produkcję. Nie wdrażać archiwalnego forum bez aktualnej kopii danych oraz planu wycofania.

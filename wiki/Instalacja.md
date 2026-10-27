# Instalacja

Poniższe kroki odtwarzają sposób opisany w materiałach projektu. Dotyczą historycznego kodu phpBB by Przemo 1.12.9 i nie stanowią potwierdzenia, że oprogramowanie działa bezpiecznie na współczesnym hostingu.

## Przed instalacją

1. Przygotuj odizolowane środowisko testowe. Oryginalne materiały wymieniają PHP 4.0+ oraz MySQL 3.2+ (wspominają też testy z PHP 5.1.2 i MySQL 5.1.4). Są to dane historyczne; współczesne wersje PHP usunęły API używane przez stare aplikacje.
2. Utwórz bazę danych oraz użytkownika z prawami wymaganymi do tworzenia tabel i zapisu danych. Ustal host bazy (w dawnych instalacjach często `localhost`), nazwę bazy, login i hasło.
3. Wykonaj kopię zapasową, jeśli instalujesz w środowisku zawierającym jakiekolwiek istniejące dane.

## Kroki

1. Wgraj zawartość pakietu do katalogu forum na serwerze.
2. Otwórz w przeglądarce `https://twoja-domena.example/forum/install.php`, zmieniając domenę i ścieżkę na właściwe.
3. Sprawdź podstawowe ustawienia: domenę bez `http://`, port serwera oraz ścieżkę forum (np. `/forum`).
4. Wybierz dostępny sterownik bazy danych i podaj host/DSN, nazwę bazy, użytkownika, hasło i prefiks tabel. Inny prefiks pozwala historycznie rozdzielić tabele kilku instalacji we wspólnej bazie.
5. Podaj nazwę, hasło i e-mail konta administratora.
6. Uruchom instalację. Instalator tworzy strukturę i dane początkowe, konto administratora oraz plik `config.php`.
7. Jeżeli PHP nie może zapisać `config.php`, zachowaj wygenerowany plik z instalatora i wgraj go ręcznie do katalogu głównego forum.
8. Po zakończeniu usuń lub zablokuj publiczny dostęp do `install.php`. Sprawdź także uprawnienia katalogów potrzebnych do zapisu.

## Po instalacji

- Zaloguj się do Panelu Administracyjnego i sprawdź działanie forum.
- Oryginalna dokumentacja opisuje narzędzie „Kontrola Systemu” / CheckFiles, które sprawdza pliki, prawa zapisu i strukturę bazy.
- Katalogi danych wymienione w dokumentacji to m.in. `files/`, `images/avatars/`, `images/photos/`, `images/signatures/`, `images/smiles/`, `templates/` i `language/`.
- Zachowaj kopię `config.php` poza katalogiem publicznym. Zawiera poświadczenia bazy danych.

## Oryginalne źródła

- [Instrukcja instalacji w Wayback Machine](https://web.archive.org/web/20260408150902/http://www.przemo.org/phpBB2/index.php?page=install)
- [`docs/readme.html` w repozytorium](https://github.com/Sh4d0v/phpbb_by_przemo/blob/main/docs/readme.html)

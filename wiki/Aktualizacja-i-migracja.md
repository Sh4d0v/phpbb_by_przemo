# Aktualizacja i migracja

Procedury poniżej pochodzą z materiałów archiwalnych. Aktualizatory modyfikują bazę danych i mogą uszkodzić forum przy niezgodnej wersji, przerwaniu operacji lub błędnym prefiksie.

## Kopia zapasowa

Przed każdą migracją wykonaj kopię całej bazy (struktura i dane) oraz wszystkich plików forum. Sprawdź, czy kopie da się odczytać. Najpierw przeprowadź procedurę na kopii w odizolowanym środowisku.

## Aktualizacja istniejącej instalacji by Przemo

Dokumentacja dołączona do pakietu opisuje aktualizator dla wersji 1.5–1.9.5, natomiast kod `scripts/update.php` w tej kopii identyfikuje aktualizację bazy z 1.12.8 do 1.12.9. Stosuj instrukcję zgodną z faktyczną wersją źródłową; nie zakładaj, że jedna ścieżka obejmuje wszystkie wydania.

1. Zaloguj się jako administrator.
2. Wgraj katalog `scripts/` z właściwego pakietu do katalogu forum.
3. Otwórz `/scripts/update.php` i uruchom aktualizację bazy zgodnie z komunikatami.
4. Sprawdź zgłoszone niewykonane instrukcje SQL. Nie wykonuj w ciemno pliku `update.sql` ręcznie — oryginalny komunikat ostrzega, że nie zawiera wszystkich instrukcji aktualizatora.
5. Wgraj pliki nowej wersji, zachowując dotychczasowy `config.php`.
6. Sprawdź Panel Administracyjny, kontrolę systemu, prawa zapisu, style i konfigurację.

W archiwalnej instrukcji aktualizacji do 1.12.9 zaleca się najpierw uruchomić skrypt aktualizacji bazy, a następnie nadpisać pliki forum, pozostawiając stary `config.php`.

## Migracja z oryginalnego phpBB 2.0.x

Archiwalna strona projektu opisuje migrację danych przez `/scripts/update.php`, a następnie wymianę plików. Zalecała przed operacją ustawić styl `subSilver` jako domyślny i dla użytkowników oraz dostosować style do wersji Przema. Wspomniane zapytanie `UPDATE phpbb_users SET user_style = 1;` wymaga zmiany `phpbb_` na rzeczywisty prefiks tabel.

Nie wykonuj tego zapytania bez sprawdzenia schematu, prefiksu i kopii bazy. Po migracji zweryfikuj style i konta, a następnie sprawdź wynik działania forum.

## Oryginalne źródła

- [Instrukcja instalacji, aktualizacji i migracji w Wayback Machine](https://web.archive.org/web/20260408150902/http://www.przemo.org/phpBB2/index.php?page=install)
- [`scripts/update.php`](https://github.com/Sh4d0v/phpbb_by_przemo/blob/main/scripts/update.php)
- [`docs/readme.html`](https://github.com/Sh4d0v/phpbb_by_przemo/blob/main/docs/readme.html)

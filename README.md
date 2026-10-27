# phpBB modified by Przemo

Polska modyfikacja forum **phpBB 2**, znana jako *phpBB by Przemo*. Projekt rozszerzał phpBB 2 o rozbudowany zestaw funkcji administracyjnych i użytkowych. Ta kopia repozytorium zawiera wersję **1.12.9**.

> **Uwaga:** to archiwalne oprogramowanie. Wymagania podane w oryginalnych materiałach (PHP 4+ i MySQL 3.2+) pochodzą z czasów jego powstania i nie są rekomendacją dla współczesnego serwera. Projekt używa przestarzałych interfejsów PHP i nie był tu zweryfikowany pod kątem bezpieczeństwa ani zgodności ze współczesnymi wersjami PHP/MySQL. Nie wystawiaj niezabezpieczonej, nieaktualizowanej instalacji do publicznego Internetu.

## Co zawiera

- klasyczny silnik phpBB 2 z modyfikacjami Przema;
- rozbudowany Panel Administracyjny i funkcje, które można konfigurować;
- instalator w `install.php`;
- narzędzia aktualizacji bazy w `scripts/`;
- dokumentację historyczną w [`docs/`](docs/), w tym oryginalne informacje o instalacji, konfiguracji, aktualizacji i deinstalacji.

## Instalacja

1. Przygotuj serwer WWW obsługujący wersję PHP i bazę danych zgodne z tym archiwalnym kodem. Oryginalny `docs/readme.html` wymienia PHP 4.0+ i MySQL 3.2+; są to wyłącznie historyczne informacje.
2. Utwórz bazę danych i konto z uprawnieniami do tworzenia oraz modyfikowania tabel.
3. Wgraj pliki forum do katalogu docelowego.
4. Otwórz `https://twoja-domena.example/forum/install.php` (dostosuj adres i ścieżkę) i wypełnij formularz. Podaj host, nazwę bazy, użytkownika, hasło, prefiks tabel oraz dane administratora.
5. Jeśli instalator nie może zapisać `config.php`, pobierz wygenerowany plik i umieść go ręcznie w katalogu głównym forum.
6. Po zakończeniu instalacji usuń lub zablokuj dostęp do instalatora i sprawdź uprawnienia plików oraz katalogów.

Szczegóły i zastrzeżenia znajdują się w [Wiki: Instalacja](https://github.com/Sh4d0v/phpbb_by_przemo/wiki/Instalacja) oraz w oryginalnym [archiwum instrukcji instalacji](https://web.archive.org/web/20260408150902/http://www.przemo.org/phpBB2/index.php?page=install).

## Aktualizacja i migracja

Przed każdą operacją wykonaj kopię plików i bazy danych. Archiwalne instrukcje opisują użycie `scripts/update.php` do aktualizacji bazy, a następnie wgranie plików odpowiedniej wersji z zachowaniem istniejącego `config.php`. Szczegóły zależą od wersji źródłowej — przeczytaj [Wiki: Aktualizacja i migracja](https://github.com/Sh4d0v/phpbb_by_przemo/wiki/Aktualizacja-i-migracja) przed rozpoczęciem.

## Dokumentacja

- [Wiki projektu](https://github.com/Sh4d0v/phpbb_by_przemo/wiki)
- [Informacje dołączone do pakietu](docs/readme.html)
- [Autorzy](docs/AUTHORS)
- [Licencja GNU GPL v2](docs/COPYING)
- [Instalacja w Wayback Machine](https://web.archive.org/web/20260408150902/http://www.przemo.org/phpBB2/index.php?page=install)

## Licencja i autorstwo

Plik [`docs/COPYING`](docs/COPYING) zawiera GNU General Public License w wersji 2. [`docs/AUTHORS`](docs/AUTHORS) wskazuje autorstwo modyfikacji Przema i odsyła do oryginalnej listy autorów phpBB. Zachowaj istniejące informacje o prawach autorskich i licencji przy dalszym rozpowszechnianiu.

To repozytorium jest archiwum kodu; nie należy z niego wyciągać wniosków o bieżącym wsparciu, poprawkach bezpieczeństwa ani oficjalnym statusie projektu.


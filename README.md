# phpBB modified by Przemo 1.12.9

Archiwalna, polska modyfikacja forum **phpBB 2** autorstwa Przema. Ta gałąź zawiera pierwotny snapshot wersji 1.12.9 z repozytorium; ten plik README jest jedynie czytelnym wprowadzeniem do dołączonego pakietu.

> **Oprogramowanie historyczne.** Wymagania w oryginalnej dokumentacji (PHP 4.0+ i MySQL 3.2+) opisują realia z czasu wydania, a nie zalecane środowisko dzisiaj. Kod jest przestarzały, nie był sprawdzany pod kątem współczesnego bezpieczeństwa i może nie działać na aktualnych wersjach PHP/MySQL. Nie wystawiaj go publicznie bez własnego audytu i odizolowanego środowiska.

## Dokumentacja pakietu

- [Oryginalne informacje i instrukcja](docs/readme.html)
- [Autorzy](docs/AUTHORS)
- [Licencja GNU GPL](docs/COPYING)
- [Instrukcja instalacji z archiwum](https://web.archive.org/web/20260408150902/http://www.przemo.org/phpBB2/index.php?page=install)

## Skrócona instalacja

1. Utwórz bazę danych MySQL i konto z uprawnieniami do tworzenia i zmiany tabel.
2. Wgraj pliki tej gałęzi do katalogu forum.
3. Otwórz `/install.php` w przeglądarce i podaj dane serwera, bazy danych, prefiks tabel oraz konto administratora.
4. Jeśli instalator nie zapisze `config.php`, umieść wygenerowany plik ręcznie w katalogu głównym forum.
5. Po instalacji usuń albo zablokuj dostęp do instalatora i sprawdź uprawnienia katalogów.

Przed instalacją na istniejącym serwerze zapoznaj się z oryginalną dokumentacją. Przed migracją lub aktualizacją wykonaj kopię zapasową plików i bazy.

## Zawartość

Repozytorium zawiera klasyczne pliki phpBB 2 zmodyfikowane przez Przema, Panel Administracyjny, szablony, języki, obsługę załączników i skrypty instalacyjne. Szczegóły funkcji oraz historyczne procedury opisuje `docs/readme.html`.

## Licencja i pochodzenie

W pakiecie znajduje się tekst GNU GPL w [`docs/COPYING`](docs/COPYING). Informacje o autorach znajdują się w [`docs/AUTHORS`](docs/AUTHORS). Zachowaj dołączone oznaczenia licencyjne i autorskie.

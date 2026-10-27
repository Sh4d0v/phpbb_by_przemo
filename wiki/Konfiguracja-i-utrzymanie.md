# Konfiguracja i utrzymanie

## Panel Administracyjny

Oryginalna dokumentacja opisuje rozbudowany Panel Administracyjny oraz możliwość włączania i wyłączania dodatkowych funkcji. Wartościowe informacje o ustawieniach znajdują się w opisach poszczególnych ekranów panelu. Repozytorium nie zawiera pełnego, aktualnego podręcznika użytkownika.

## Kontrola systemu

„Kontrola Systemu” (CheckFiles) sprawdza poprawność plików PHP, prawa zapisu plików i katalogów oraz strukturę bazy danych. Oryginalne materiały wskazują, że lokalne modyfikacje plików PHP mogą wymagać wpisów informacyjnych w `includes/mods_info/info`, aby kontrola rozpoznawała je jako zamierzone zmiany.

Nie ustawiaj praw zapisu szerzej niż jest to konieczne. Po wykonaniu operacji, które wymagają zapisu, przywróć możliwie restrykcyjne uprawnienia.

## Dane plikowe i style

Dokumentacja wymienia katalogi przechowujące m.in. załączniki (`files/`), awatary, zdjęcia profilu, sygnatury i emotikony pod `images/`, style w `templates/` oraz języki w `language/`. Obrazki rang i ikon tematów w tej wersji są zależne od stylu (np. `templates/NazwaStylu/images/ranks/` i `icon/`).

Uwzględnij te pliki w kopiach zapasowych razem z bazą danych.

## Bezpieczeństwo archiwum

Kod phpBB 2 i jego zależności są historyczne. Przed jakimkolwiek uruchomieniem wykonaj audyt, ogranicz dostęp sieciowy i użyj odizolowanego środowiska. Nie przechowuj kopii produkcyjnych poświadczeń w repozytorium.

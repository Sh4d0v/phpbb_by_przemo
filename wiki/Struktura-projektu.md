# Struktura projektu

Najważniejsze katalogi tej kopii:

| Ścieżka | Rola według zawartości repozytorium |
| --- | --- |
| `admin/` | pliki Panelu Administracyjnego |
| `includes/` | wspólny kod aplikacji, biblioteki i modyfikacje |
| `language/` | pliki językowe |
| `templates/` | szablony i zasoby stylów |
| `images/` | grafiki forum oraz katalogi danych użytkowników |
| `files/` | miejsce na załączniki |
| `scripts/` | skrypty aktualizacji i narzędzia administracyjne |
| `stat_modules/` | moduły statystyk |
| `attach_mod/` | składniki modyfikacji załączników |
| `docs/` | oryginalne materiały, autorzy i licencja |

Punkty wejścia aplikacji znajdują się w katalogu głównym, m.in. `index.php`, `viewforum.php`, `viewtopic.php`, `posting.php`, `login.php` i `install.php`. `config.php` zawiera dane połączenia z bazą; w repozytorium tej kopii jest pusty plik startowy.

Kod aktualizatora opisuje wersję 1.12.8 jako źródłową, a `scripts/update.sql` wskazuje wersję docelową 1.12.9. Weryfikuj wersję rzeczywistej instalacji przed uruchomieniem narzędzi migracyjnych.

# Value Objects

| Value Object | Description | Documentation |
|--------------|-------------|---------------|
| AmountVO | Montant monétaire avec précision à 2 décimales | [AmountVO](value-objects/amount-vo.md) |
| EmailVO | Adresse email validée | [EmailVO](value-objects/email-vo.md) |
| PostalCodeVO | Code postal français à 5 chiffres | [PostalCodeVO](value-objects/postal-code-vo.md) |
| AddressVO | Adresse postale complète | [AddressVO](value-objects/address-vo.md) |
| MoneyVO | Montant monétaire associé à une devise | [MoneyVO](value-objects/money-vo.md) |
| DateTimeVO | Date/heure ISO 8601 avec gestion des fuseaux horaires | [DateTimeVO](value-objects/date-time-vo.md) |
| PhoneNumberVO | Numéro de téléphone international (E.164) avec indicatif | [PhoneNumberVO](value-objects/phone-number-vo.md) |
| SlugVO | Slug URL-friendly avec normalisation automatique | [SlugVO](value-objects/slug-vo.md) |
| IdVO | Identifiant entier positif | [IdVO](value-objects/id-vo.md) |
| CoordinatesVO | Coordonnées géographiques (latitude/longitude) avec validation des plages et calcul de distance (formule de Haversine) | [CoordinatesVO](value-objects/coordinates-vo.md) |
| TimeVO | Heure de la journée (H:i:s) avec arithmétique et comparaisons, support du passage de minuit | [TimeVO](value-objects/time-vo.md) |
| LocationVO | Paire de coordonnées `[longitude, latitude]` au format LocationIQ | [LocationVO](value-objects/location-vo.md) |
| ItineraryVO | Itinéraire ordonné entre deux points avec points intermédiaires réordonnés par heuristique du plus proche voisin et calcul de distances Haversine | [ItineraryVO](value-objects/itinerary-vo.md) |
| ItineraryLegVO | Segment d'un itinéraire (from → to) avec distance Haversine et unité | [ItineraryLegVO](value-objects/itinerary-leg-vo.md) |
| ItinerarySummaryVO | Agrégat statistique d'un itinéraire : nombre de segments, points, distances totale/moyenne/min/max | [ItinerarySummaryVO](value-objects/itinerary-summary-vo.md) |
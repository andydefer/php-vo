// resources/js/types/enums/Continent.ts

export enum Continent {
    AFRICA = 'AF',
    ANTARCTICA = 'AN',
    ASIA = 'AS',
    EUROPE = 'EU',
    NORTH_AMERICA = 'NA',
    OCEANIA = 'OC',
    SOUTH_AMERICA = 'SA',
}

export const ContinentLabels: Record<Continent, string> = {
    [Continent.AFRICA]: 'Afrique',
    [Continent.ANTARCTICA]: 'Antarctique',
    [Continent.ASIA]: 'Asie',
    [Continent.EUROPE]: 'Europe',
    [Continent.NORTH_AMERICA]: 'Amérique du Nord',
    [Continent.OCEANIA]: 'Océanie',
    [Continent.SOUTH_AMERICA]: 'Amérique du Sud',
};

export function getContinentLabel(continent: Continent): string {
    return ContinentLabels[continent] ?? continent;
}

export const ContinentValues: Continent[] = Object.values(Continent);
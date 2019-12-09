

	INSERT INTO `casacaliente_05122019`.`dblloguersadicional`
(`idllogueradicional`,
`reflloguers`,
`personas`,
`entrada`,
`sortida`,
`taxapersona`,
`taxaturistica`,
`menores`)


SELECT 
    '',
    r.idlloguer,
    (CASE
        WHEN r.numpertax = 0 THEN r.N_PERSONES
        ELSE r.numpertax
    END) as mayores,
    r.entrada,
    r.sortida,
    /*r.numpertax,
    r.N_PERSONES,*/
    (case when DATEDIFF(r.sortida, r.entrada) < 7 then (CASE
            WHEN r.N_PERSONES = 0 THEN r.numpertax
            ELSE r.N_PERSONES
        END) * 1 * 20 else (CASE
            WHEN r.N_PERSONES = 0 THEN r.numpertax
            ELSE r.N_PERSONES
        END) * DATEDIFF(r.sortida, r.entrada) / 7 * 20 end) as taxapersona,
    (CASE
        WHEN
            1 * DATEDIFF(r.sortida, r.entrada) * (CASE
                WHEN r.N_PERSONES = 0 THEN r.numpertax
                ELSE r.N_PERSONES
            END) > r.maxtaxa
        THEN
            (CASE
                WHEN r.N_PERSONES = 0 THEN r.numpertax
                ELSE r.N_PERSONES
            END) * r.maxtaxa
        ELSE (CASE
            WHEN r.N_PERSONES = 0 THEN r.numpertax
            ELSE r.N_PERSONES
        END) * r.taxa * DATEDIFF(r.sortida, r.entrada)
    END) as taxaturistica,
    (CASE
        WHEN r.N_PERSONES = 0 THEN r.numpertax
        ELSE r.N_PERSONES
    END) - (CASE
        WHEN r.numpertax = 0 THEN r.N_PERSONES
        ELSE r.numpertax
    END) as menores
    
FROM
    (SELECT 
        l.idlloguer,
            MAX(dl.`N_PERSONES`) AS N_PERSONES,
            l.numpertax,
            l.entrada,
            l.sortida,
            l.maxtaxa,
            l.taxa
    FROM
        `casacaliente_05122019`.`dblloguers` l
    LEFT JOIN `casacalientem`.`detall lloguer` dl ON l.idlloguer = dl.`ID LLOGUER`
    GROUP BY l.idlloguer , l.numpertax , l.entrada , l.sortida , l.maxtaxa , l.taxa) r

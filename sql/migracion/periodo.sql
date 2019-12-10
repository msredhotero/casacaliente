INSERT INTO `casacaliente_05122019`.`dbperiodos`
(`idperiodo`,
`periodo`,
`any`,
`desdeperiode`,
`finsaperiode`,
reflocatarios,
idviejo)

SELECT '',
    p.`NOM PERIODE`,
    a.`any`,
    p.`DESDEPERIODE`,
    p.`FINSAPERIODE`,
    4,
    p.`ID PERIODE`
FROM `casacalientew`.`periodes` p
inner join `casacalientew`.`anys` a on a.`ID ANYS` = p.`ID ANY`


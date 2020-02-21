INSERT INTO `casacaliente_022020`.`dbperiodos`
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
inner join `casacaliente02w`.`anys` a on a.`ID ANYS` = p.`ID ANY`


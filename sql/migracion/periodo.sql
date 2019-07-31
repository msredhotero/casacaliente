INSERT INTO `casacaliente`.`dbperiodos`
(`idperiodo`,
`periodo`,
`any`,
`desdeperiode`,
`finsaperiode`)

SELECT p.`ID PERIODE`,
    p.`NOM PERIODE`,
    a.`any`,
    p.`DESDEPERIODE`,
    p.`FINSAPERIODE`
FROM `migracioncasacaliente`.`periodes` p
inner join `migracioncasacaliente`.`anys` a on a.`ID ANYS` = p.`ID ANY`


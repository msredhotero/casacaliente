INSERT INTO `casacaliente_05122019`.`dblloguers`
(`idlloguer`,
`refclientes`,
`refubicaciones`,
`datalloguer`,
`entrada`,
`sortida`,
`total`,
`numpertax`,
`persset`,
`taxa`,
`maxtaxa`,
`refestados`,
nrolloguer,
idviejo)
SELECT '',
	c.idcliente,
    u.idubicacion,
    /*l.`ID CLIENT`,
    l.`ID UBICACIO`,*/
    l.`DATALLOGUER`,
    l.`ENTRADA`,
    l.`SORTIDA`,
    l.`TOTALLLOGUER`,
    l.`N_PERS_TAX`,
    l.`PERS_SET`,
    l.`TAXA`,
    l.`MAX_TAXA`,
    1,
    l.`N_LLOGUER`,
    l.`ID LLOGUER`
FROM `casacalientew`.`lloguers` l
inner join dbclientes c on l.`ID CLIENT` = c.idclientew
inner join dbubicaciones u on l.`ID UBICACIO` = u.idubicacion


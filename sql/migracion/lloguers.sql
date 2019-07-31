INSERT INTO `casacaliente`.`dblloguers`
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
nrolloguer)
SELECT `lloguers`.`ID LLOGUER`,
    `lloguers`.`ID CLIENT`,
    `lloguers`.`ID UBICACIO`,
    `lloguers`.`DATALLOGUER`,
    `lloguers`.`ENTRADA`,
    `lloguers`.`SORTIDA`,
    `lloguers`.`TOTALLLOGUER`,
    `lloguers`.`N_PERS_TAX`,
    `lloguers`.`PERS_SET`,
    `lloguers`.`TAXA`,
    `lloguers`.`MAX_TAXA`,
    1,
    `lloguers`.`N_LLOGUER`
FROM `migracioncasacaliente`.`lloguers`;


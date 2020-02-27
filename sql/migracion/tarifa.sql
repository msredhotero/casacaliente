INSERT INTO `casacaliente_022020`.`dbtarifas`
(`idtarifa`,
`tarifa`,
`reftipoubicacion`,
`refperiodos`,
idviejo)

SELECT 
'',
/*t.`ID TARIFA`,*/
    t.`TARIFA`,
    /*ti.idtipoubicacion,
    p.idperiodo,*/
    t.`ID TIPUSUBICACIO`,
    t.`ID PERIODE`,
    t.`ID TARIFA`
    /*0*/
FROM `casacaliente02w`.`tarifa` t
/*
inner join dbperiodos p on p.idperiodo = t.`ID PERIODE` and p.reflocatarios = 3
inner join tbtipoubicacion ti on ti.idtipoubicacion = t.`ID TIPUSUBICACIO` and ti.reflocatarios = 3
*/
inner join dbperiodos p on p.idviejo = t.`ID PERIODE` and p.reflocatarios = 4
inner join tbtipoubicacion ti on ti.idviejo = t.`ID TIPUSUBICACIO` and ti.reflocatarios = 4


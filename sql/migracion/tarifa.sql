INSERT INTO `casacaliente_05122019`.`dbtarifas`
(`idtarifa`,
`tarifa`,
`reftipoubicacion`,
`refperiodos`,
idviejo)

SELECT 
'',
    t.`TARIFA`,
    ti.idtipoubicacion,
    p.idperiodo,
    /*t.`ID TIPUSUBICACIO`,
    t.`ID PERIODE`,*/
    t.`ID TARIFA`

FROM `casacalientew`.`tarifa` t
inner join dbperiodos p on p.idviejo = t.`ID PERIODE` and p.reflocatarios = 4
inner join tbtipoubicacion ti on ti.idviejo = t.`ID TIPUSUBICACIO` and ti.reflocatarios = 4


update	dbtarifas t
inner join (
SELECT 
    min(idperiodo) as idperiodobueno,max(idperiodo) as idperiodocambio, desdeperiode, finsaperiode
FROM
    casacaliente.dbperiodos
WHERE
    any = 2019 AND reflocatarios = 3
group by desdeperiode, finsaperiode) c
on t.refperiodos = c.idperiodocambio
set t.refperiodos = c.idperiodobueno

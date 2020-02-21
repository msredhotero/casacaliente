
update	dblloguers l
set l.datalloguer = concat(substring(l.datalloguer,9,2),'/',substring(l.datalloguer,6,2),'/',substring(l.datalloguer,1,4))
where		l.datalloguer is not null and l.idviejo <> 0
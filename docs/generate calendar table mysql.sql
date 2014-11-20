-- create staging table to generate dummy rows
create table ints(i tinyint);
insert into ints values(0),(1),(2),(3),(4),(5),(6),(7),(8),(9);

-- create staging table
create table dates (
	dia date primary key
);

-- put initial date value
insert into dates values ('1986-01-01');

-- create view to generate subsequent dates
create view v1 as 
SELECT (select max(dia)+1 from dates) + INTERVAL t.i*100 + u.i*10 + v.i DAY AS Date
FROM ints AS t
JOIN ints AS u
JOIN ints AS v
WHERE ( t.i*100 + u.i*10 + v.i ) < 1000
ORDER BY Date;

-- insert new dates into staging table. Each run will generate 1000 rows starting from max(date) from
-- the staging table
insert into dates select * from v1;

-- once satisfied with all generated values into staging table, copy to permanent table
insert into calendario (calendario_dia) select dia from dates;

-- update all the other rows based on the day
update calendario
set calendario_dia_label = DATE_FORMAT(calendario_dia, '%d/%b/%Y')
  , calendario_semana = DATE_FORMAT(calendario_dia, '%Y%u')
  , calendario_semana_label = DATE_FORMAT(calendario_dia, 'W%Y%u')
  , calendario_mes = DATE_FORMAT(calendario_dia, '%Y%m')
  , calendario_mes_label = DATE_FORMAT(calendario_dia, '%M/%Y')
  , calendario_ano = DATE_FORMAT(calendario_dia, '%Y')
  , calendario_ano_label = DATE_FORMAT(calendario_dia, '%Y')
  , calendario_trimestre = (case when month(calendario_dia) <=3 then concat(year(calendario_dia),'01')
						        when month(calendario_dia) between 4 and 6 then concat(year(calendario_dia),'02')
						        when month(calendario_dia) between 7 and 9 then concat(year(calendario_dia),'03')
								when month(calendario_dia) between 10 and 12 then concat(year(calendario_dia),'04') 
							end)
  , calendario_trimestre_label = (case when month(calendario_dia) <=3 then concat(year(calendario_dia),'Q1')
						        when month(calendario_dia) between 4 and 6 then concat(year(calendario_dia),'Q2')
						        when month(calendario_dia) between 7 and 9 then concat(year(calendario_dia),'Q3')
								when month(calendario_dia) between 10 and 12 then concat(year(calendario_dia),'Q4') 
							end)
  , calendario_semestre = (case when month(calendario_dia) <=6 then concat(year(calendario_dia),'01')
						        else concat(year(calendario_dia),'02')
							end)
  , calendario_semestre_label = (case when month(calendario_dia) <=6 then concat(year(calendario_dia),'S1')
						        else concat(year(calendario_dia),'S2')
							end)
  , calendario_dia_semana = DATE_FORMAT(calendario_dia, '%w')
  , calendario_dia_semana_label = DATE_FORMAT(calendario_dia, '%W')
;


-- check if the values are correct for the current date
select * from calendario where calendario_dia = curdate();




-- if necessary, uncomment these rows to start all over
/* ALTER TABLE calendario AUTO_INCREMENT = 1;
delete from calendario;
SELECT * FROM calendario;*/

-- drop staging resources
drop view v1;
drop table dates;
drop table ints;
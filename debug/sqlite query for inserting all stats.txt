delete from statistics;

/* total damage events */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "damage", "_", sum(count), sum(sum)
from collated_events
where tags like "%damage%"
group by actor, target;

/* damaging attacks total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "damage.attack", "_", sum(count), sum(sum)
from collated_events
where tags like "%damage%" and tags like "%attack%"
group by actor, target;

/* damaging attacks by action */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "damage.attack.action", action, sum(count), sum(sum)
from collated_events
where tags like "%damage%" and tags like "%attack%"
group by actor, target, action;

/* damaging incidents total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "damage.incident", "_", sum(count), sum(sum)
from collated_events
where tags like "%damage%" and tags like "%incident%"
group by actor, target;

/* damaging incidents by incident result type */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "damage.incident.result", result, sum(count), sum(sum)
from collated_events
where tags like "%damage%" and tags like "%incident%"
group by actor, target, result;

/* damage by stat type */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "damage.stat", stat, sum(count), sum(sum)
from collated_events
where tags like "%damage%"
group by actor, target, stat;

/* damage by damage type */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "damage.type", type, sum(count), sum(sum)
from collated_events
where tags like "%damage%"
group by actor, target, type;


/* total attack events */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "attack", "_", sum(count), sum(sum)
from collated_events
where tags like "%attack%"
group by actor, target;

/* damaging attacks */
/* captured via damage.attack */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "attack.damage", grouping, count, sum
from statistics
where ref="damage.attack";

/* attack hits total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "attack.hits", "_", sum(count), sum(sum)
from collated_events
where tags like "%attack%" and tags like "%hit%"
group by actor, target;

/* attack hits results */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "attack.hits.result", result, sum(count), sum(sum)
from collated_events
where tags like "%attack%" and tags like "%hit%"
group by actor, target, result;

/* attack failures total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "attack.failures", "_", sum(count), sum(sum)
from collated_events
where tags like "%attack%" and (tags like "%defend%" or tags like "%miss%")
group by actor, target;

/* attack failures by result */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "attack.failed.result", result, sum(count), sum(sum)
from collated_events
where tags like "%attack%" and (tags like "%defend%" or tags like "%miss%")
group by actor, target, result;

/* attack effects applied total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "attack.effect", "_", sum(count), sum(sum)
from collated_events
where tags like "%attack%" and tags like "%effect%"
group by actor, target;

/* attack effects applied by action */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "attack.effect.action", action, sum(count), sum(sum)
from collated_events
where tags like "%attack%" and tags like "%effect%"
group by actor, target, action;


/* heal events total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "heal", "_", sum(count), sum(sum)
from collated_events
where tags like "%heal%"
group by actor, target;

/* heals by stat */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "heal.stat", stat, sum(count), sum(sum)
from collated_events
where tags like "%heal%"
group by actor, target, stat;

/* heals by action */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "heal.action", action, sum(count), sum(sum)
from collated_events
where tags like "%heal%" and tags like "%hit%"
group by actor, target, action;

/* heals by incidents total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "heal.incident", "_", sum(count), sum(sum)
from collated_events
where tags like "%heal%" and tags like "%incident%"
group by actor, target;

/* heals by incident by result */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "heal.incident.result", result, sum(count), sum(sum)
from collated_events
where tags like "%heal%" and tags like "%incident%"
group by actor, target, result;

/* heals by magnitude */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "heal.magnitude", result, sum(count), sum(sum)
from collated_events
where tags like "%heal%" and tags like "%hit%"
group by actor, target, result;


/* all incidents */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "incident", "_", sum(count), sum(sum)
from collated_events
where tags like "%incident%"
group by actor, target;

/* incident reflects total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "incident.reflect", result, sum(count), sum(sum)
from collated_events
where tags like "%incident%" and tags like "%reflect%"
group by actor, target, result;

/* incident damaging reflects */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "incident.reflect.damage", "_", sum(count), sum(sum)
from collated_events
where tags like "%incident%" and tags like "%reflect%" and tags like "%damage%"
group by actor, target;

/* incident healing reflects */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "incident.reflect.heal", "_", sum(count), sum(sum)
from collated_events
where tags like "%incident%" and tags like "%reflect%" and tags like "%heal%"
group by actor, target;

/* incident interrupts total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "incident.interrupt", "_", sum(count), sum(sum)
from collated_events
where tags like "%incident%" and tags like "%interrupt%"
group by actor, target;

/* incident cc break total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "incident.ccbreak", "_", sum(count), sum(sum)
from collated_events
where tags like "%incident%" and tags like "%ccbreak%"
group by actor, target;

/* incident cc breaks by type */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "incident.ccbreak.type", type, sum(count), sum(sum)
from collated_events
where tags like "%incident%" and tags like "%ccbreak%"
group by actor, target, type;

/* incident successful dispel total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "incident.dispel", result, sum(count), sum(sum)
from collated_events
where tags like "%incident%" and tags like "%dispel%" and result="dispelled"
group by actor, target, result;

/* incident dispel by action */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "incident.dispel.action", action, sum(count), sum(sum)
from collated_events
where tags like "%incident%" and tags like "%dispel%" and result="dispelled"
group by actor, target, action;

/* incident dispel nothing */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "incident.dispel.nothing", "_", sum(count), sum(sum)
from collated_events
where tags like "%incident%" and tags like "%dispel%" and result="nothing"
group by actor, target;


/* support total events */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "support", "_", sum(count), sum(sum)
from collated_events
where tags like "%support%"
group by actor, target;

/* support effects applied total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "support.effect", "_", sum(count), sum(sum)
from collated_events
where tags like "%support%" and tags like "%effect%"
group by actor, target;

/* support by effects applied by action */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "support.effect.action", action, sum(count), sum(sum)
from collated_events
where tags like "%support%" and tags like "%effect%"
group by actor, target, action;

/* support successful cures total */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "support.cure", "_", sum(count), sum(sum)
from collated_events
where tags like "%support%" and tags like "%cure%" and result="cured"
group by actor, target;

/* support successful cures by action */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "support.cure.action", action, sum(count), sum(sum)
from collated_events
where tags like "%support%" and tags like "%cure%" and result="cured"
group by actor, target, action;

/* support cured nothing */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, target, "support.cure.nothing", "_", sum(count), sum(sum)
from collated_events
where tags like "%support%" and tags like "%cure%" and result="nothing"
group by actor, target;


/* actor totals */
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, "_", ref, grouping, sum([count]), sum([sum])
from statistics
group by actor, ref, grouping;


/* ref totals */
/*
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, "_", ref, "_", sum([count]), sum([sum])
from statistics
group by actor, ref;
*/
/* grouping totals */
/*
insert into statistics (actor, target, ref, grouping, count, sum)
select actor, "_", ref, grouping, sum([count]), sum([sum])
from statistics
group by actor, ref, grouping;
*/
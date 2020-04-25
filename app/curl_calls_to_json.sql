select array_to_json(array_agg(row_to_json(t))) json
from (
  select array_agg(time_total * 1000 order by c.created_at) as y,
         array_agg(to_char(c.created_at, 'MM-DD (HH24:00 - :59)') order by c.created_at) as x,
         a.alias as name,
         'box' as type,
         false as boxpoints
  from curl c
  join alias a on c.url_effective = a.url
  group by a.alias
) t;

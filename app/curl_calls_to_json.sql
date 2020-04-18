select
  array_to_json(array_agg(row_to_json(t))) json
from
  (
  select
    a.alias as name,
    'scatter' as type,
    array_agg(c.created_at order by c.created_at) as x,
    array_agg(c.time_total * 1000 order by c.created_at) as y
  from
    curl c
  join
    alias a on c.url_effective = a.url
  group by
    url_effective, alias ) t

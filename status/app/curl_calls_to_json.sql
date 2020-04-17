select
  array_to_json(array_agg(row_to_json(t))) json
from
  (
  select
    url_effective as name,
    'scatter' as type,
    array_agg(created_at order by created_at) as x,
    array_agg(time_total * 1000 order by created_at) as y
  from
    curl
  group by
    url_effective ) t



スケジュールの重複検索（ルートID、出発時刻、到着時刻が同じものを探す）
SELECT route_id_id, start_hour, start_minute, end_hour, end_minute, COUNT(*) as count
FROM schedule
GROUP BY route_id_id, start_hour, start_minute, end_hour, end_minute
HAVING COUNT(*) > 1;

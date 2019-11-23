-- 如果status=1 ,返回失败，如果status=2 ,返回成功，否则返回其他

SELECT status,
 CASE
WHEN status='2' THEN
	'成功'
WHEN status='1' THEN
	'失败'
ELSE
	'其他'
END
AS cstatus
FROM
	fc_attendance_rolls
ORDER BY
	created DESC
-- Just to show the table structure we have to create this table on the fly
CREATE TABLE IF NOT EXISTS guestbook
(
  id      INTEGER PRIMARY KEY AUTOINCREMENT,
  name    varchar(100) DEFAULT NULL,
  address text,
  phone   varchar(50),
  ttime   timestamp
)
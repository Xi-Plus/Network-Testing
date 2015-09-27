CREATE TABLE IF NOT EXISTS `speedtest` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `download` float NOT NULL,
  `upload` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

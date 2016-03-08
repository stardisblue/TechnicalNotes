/**
 * technotes:
 - id
 - titre
 - content
 - date modification
 - date creation
 - auteur (user_id)
 */
CREATE TABLE IF NOT EXISTS `technotes` (
  `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`             VARCHAR(250) NOT NULL,
  `content`           TEXT         NOT NULL,
  `user_id`           INT UNSIGNED NOT NULL,
  `date_modification` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_creation`     DATETIME              DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

/**
 * Questions:
 - id
 - contenu
 - date modification
 - date creation
 - auteur (user_id)
 */
CREATE TABLE IF NOT EXISTS `questions` (
  `id`                INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `content`           VARCHAR(1000) NOT NULL,
  `date_modification` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_creation`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id`           INT UNSIGNED  NOT NULL
);


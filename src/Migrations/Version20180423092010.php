<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180423092010 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE travel (id INT AUTO_INCREMENT NOT NULL, userid_id INT NOT NULL, carid_id INT NOT NULL, starthour DATETIME NOT NULL, startaddress VARCHAR(255) NOT NULL, startcity VARCHAR(30) NOT NULL, endaddress VARCHAR(255) NOT NULL, endcity VARCHAR(30) NOT NULL, startzipcode VARCHAR(30) NOT NULL, endzipcode VARCHAR(30) NOT NULL, price INT NOT NULL, datecreated DATETIME NOT NULL, title VARCHAR(40) NOT NULL, INDEX IDX_2D0B6BCE58E0A285 (userid_id), INDEX IDX_2D0B6BCEE90BFAE0 (carid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE travel_user (travel_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_46CB35E4ECAB15B3 (travel_id), INDEX IDX_46CB35E4A76ED395 (user_id), PRIMARY KEY(travel_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, userlauncherid_id INT NOT NULL, usertargetid_id INT NOT NULL, travelid_id INT NOT NULL, content VARCHAR(255) DEFAULT NULL, datecreated DATETIME NOT NULL, rating INT NOT NULL, INDEX IDX_9474526CF2E47134 (userlauncherid_id), INDEX IDX_9474526C2B55CE69 (usertargetid_id), INDEX IDX_9474526CE9390DE1 (travelid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, brand VARCHAR(20) NOT NULL, plate VARCHAR(15) NOT NULL, color VARCHAR(10) DEFAULT NULL, placemax INT NOT NULL, putintoservicedate DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, paymenttype_id INT NOT NULL, debtor_id INT NOT NULL, creditor_id INT NOT NULL, value INT NOT NULL, INDEX IDX_723705D11947F086 (paymenttype_id), INDEX IDX_723705D1B043EC6B (debtor_id), INDEX IDX_723705D1DF91AC92 (creditor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, userid_id INT NOT NULL, travelid_id INT NOT NULL, content VARCHAR(255) NOT NULL, datecreated DATETIME NOT NULL, INDEX IDX_B6F7494E58E0A285 (userid_id), INDEX IDX_B6F7494EE9390DE1 (travelid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(30) NOT NULL, firstname VARCHAR(30) NOT NULL, datecreated DATETIME NOT NULL, rating INT DEFAULT NULL, licencenumber VARCHAR(100) DEFAULT NULL, profilepicture VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(50) NOT NULL, roles VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE private_messages (id INT AUTO_INCREMENT NOT NULL, userlauncherid_id INT NOT NULL, usertargetid_id INT NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_7C94C13BF2E47134 (userlauncherid_id), INDEX IDX_7C94C13B2B55CE69 (usertargetid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCE58E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCEE90BFAE0 FOREIGN KEY (carid_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE travel_user ADD CONSTRAINT FK_46CB35E4ECAB15B3 FOREIGN KEY (travel_id) REFERENCES travel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE travel_user ADD CONSTRAINT FK_46CB35E4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF2E47134 FOREIGN KEY (userlauncherid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C2B55CE69 FOREIGN KEY (usertargetid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE9390DE1 FOREIGN KEY (travelid_id) REFERENCES travel (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D11947F086 FOREIGN KEY (paymenttype_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B043EC6B FOREIGN KEY (debtor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1DF91AC92 FOREIGN KEY (creditor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E58E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EE9390DE1 FOREIGN KEY (travelid_id) REFERENCES travel (id)');
        $this->addSql('ALTER TABLE private_messages ADD CONSTRAINT FK_7C94C13BF2E47134 FOREIGN KEY (userlauncherid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE private_messages ADD CONSTRAINT FK_7C94C13B2B55CE69 FOREIGN KEY (usertargetid_id) REFERENCES user (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE travel_user DROP FOREIGN KEY FK_46CB35E4ECAB15B3');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE9390DE1');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EE9390DE1');
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCEE90BFAE0');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D11947F086');
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCE58E0A285');
        $this->addSql('ALTER TABLE travel_user DROP FOREIGN KEY FK_46CB35E4A76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF2E47134');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C2B55CE69');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1B043EC6B');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1DF91AC92');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E58E0A285');
        $this->addSql('ALTER TABLE private_messages DROP FOREIGN KEY FK_7C94C13BF2E47134');
        $this->addSql('ALTER TABLE private_messages DROP FOREIGN KEY FK_7C94C13B2B55CE69');
        $this->addSql('DROP TABLE travel');
        $this->addSql('DROP TABLE travel_user');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE private_messages');
    }
}

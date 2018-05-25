<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180502120543 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCE58E0A285');
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCEE90BFAE0');
        $this->addSql('DROP INDEX IDX_2D0B6BCE58E0A285 ON travel');
        $this->addSql('DROP INDEX IDX_2D0B6BCEE90BFAE0 ON travel');
        $this->addSql('ALTER TABLE travel ADD user_id INT NOT NULL, ADD car_id INT NOT NULL, DROP userid_id, DROP carid_id');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCEC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('CREATE INDEX IDX_2D0B6BCEA76ED395 ON travel (user_id)');
        $this->addSql('CREATE INDEX IDX_2D0B6BCEC3C6F69F ON travel (car_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCEA76ED395');
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCEC3C6F69F');
        $this->addSql('DROP INDEX IDX_2D0B6BCEA76ED395 ON travel');
        $this->addSql('DROP INDEX IDX_2D0B6BCEC3C6F69F ON travel');
        $this->addSql('ALTER TABLE travel ADD userid_id INT NOT NULL, ADD carid_id INT NOT NULL, DROP user_id, DROP car_id');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCE58E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCEE90BFAE0 FOREIGN KEY (carid_id) REFERENCES car (id)');
        $this->addSql('CREATE INDEX IDX_2D0B6BCE58E0A285 ON travel (userid_id)');
        $this->addSql('CREATE INDEX IDX_2D0B6BCEE90BFAE0 ON travel (carid_id)');
    }
}

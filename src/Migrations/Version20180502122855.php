<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180502122855 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE9390DE1');
        $this->addSql('DROP INDEX IDX_9474526CE9390DE1 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE travelid_id travel_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CECAB15B3 FOREIGN KEY (travel_id) REFERENCES travel (id)');
        $this->addSql('CREATE INDEX IDX_9474526CECAB15B3 ON comment (travel_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CECAB15B3');
        $this->addSql('DROP INDEX IDX_9474526CECAB15B3 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE travel_id travelid_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE9390DE1 FOREIGN KEY (travelid_id) REFERENCES travel (id)');
        $this->addSql('CREATE INDEX IDX_9474526CE9390DE1 ON comment (travelid_id)');
    }
}

<?php

class Doctrine_Ticket_DC972_TestCase extends Doctrine_UnitTestCase
{
    public function run(DoctrineTest_Reporter $reporter = null, $filter = null)
    {
        parent::run($reporter, $filter);
        $this->manager->closeConnection($this->connection);
    }

    public function prepareData()
    {
    }

    public function prepareTables()
    {
        $this->tables = array('Ticket_DC972_Article');
        parent::prepareTables();
    }

    public function testIdentifierQuotedMultpleTimes()
    {
        $this->connection->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);

        try
        {
            $q = Doctrine_Query::create()
                ->select('a.title t')
                ->from('Ticket_DC972_Article a')
                ->having('t > 1');

            // FIXME: probably another issue, since the title is selected twice
            $this->assertEqual($q->getSqlQuery(), 'SELECT "t"."id" AS "t__id", "t"."title" AS "t__0", "t"."title" AS "t__0" FROM "ticket_dc972_article" "t" HAVING "t__0" > 1');
        }
        catch(Exception $e)
        {
            $this->fail($e->getMessage());
        }

        $this->connection->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, false);
    }
}

class Ticket_DC972_Article extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('ticket_dc972_article');
        $this->hasColumn('title', 'string', 255, array('type' => 'string', 'length' => '255'));
    }
}

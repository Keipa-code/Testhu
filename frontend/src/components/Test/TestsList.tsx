import { FC } from 'react';
import { IPagination, ITest } from '../../types/types';
import { Card, Col, Pagination, Row, Table } from 'react-bootstrap';

interface TestsListProps {
  tests: ITest[];
  tableInfo?: string;
  pagination?: IPagination;
  itemCount?: number;
}

const TestsList: FC<TestsListProps> = ({ tests, tableInfo, pagination, itemCount }) => {
  return (
    <div>
      <Table>
        <thead style={{ display: tableInfo }}>
          <tr>
            <th>Название</th>
            <th>сдало / прошло</th>
            <th> </th>
          </tr>
        </thead>
        <tbody>
          {tests.map((test) => (
            <tr key={test.id}>
              <td>
                <Card style={{ width: '18rem', border: 0 }}>
                  <Card.Body>
                    <Card.Title>{test.testName}</Card.Title>
                    {test.tags.map((tag, key) => (
                      <Card.Link key={key} href="#">
                        {tag}
                      </Card.Link>
                    ))}
                  </Card.Body>
                </Card>
              </td>
              <td>
                {test.passed} / {test.done}
              </td>
              <td>
                <a href="#">&#9658;</a>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>

      <Row className="align-items-center">
        <Col className="col-sm-8">
          <Pagination size="lg" hidden={!pagination}>
            <Pagination.First href={pagination?.['hydra:first']} />
            <Pagination.Prev href={pagination?.['hydra:previous']} />
            <Pagination.Next href={pagination?.['hydra:next']} />
            <Pagination.Last href={pagination?.['hydra:last']} />
          </Pagination>
        </Col>
        <Col className="col-sm-4">
          <p className="text-end me-3">Найдено {itemCount} тестов</p>
        </Col>
      </Row>
    </div>
  );
};

export default TestsList;

import { FC, useEffect } from 'react';
import { Card, Col, Pagination, Row, Table } from 'react-bootstrap';
import { useRootStore } from '../../RootStateContext';
import { observer } from 'mobx-react-lite';

const TestsList: FC = observer(() => {
  const { testListStore } = useRootStore();
  const page = '1';

  useEffect(() => {
    testListStore.fetchTests('?' + 'page=' + page);
  }, []);

  return (
    <div>
      <Table>
        <thead hidden={testListStore.isEmpty()}>
          <tr>
            <th>Название</th>
            <th>сдало / прошло</th>
            <th> </th>
          </tr>
        </thead>
        <tbody>
          {testListStore.tests.map((test) => (
            <tr key={test.id}>
              <td>
                <Card style={{ width: '18rem', border: 0 }}>
                  <Card.Body>
                    <Card.Title>{test.testName}</Card.Title>
                    {test.tags.map((tag, key) => (
                      <Card.Link key={key} href="#">
                        {tag.tagName}
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
          <Pagination size="lg" hidden={!testListStore.pagination}>
            <Pagination.First href={testListStore.pagination?.['hydra:first']} />
            <Pagination.Prev href={testListStore.pagination?.['hydra:previous']} />
            <Pagination.Next href={testListStore.pagination?.['hydra:next']} />
            <Pagination.Last href={testListStore.pagination?.['hydra:last']} />
          </Pagination>
        </Col>
        <Col className="col-sm-4">
          <p className="text-end me-3">Найдено {testListStore.totalItems} тестов</p>
        </Col>
      </Row>
    </div>
  );
});

export default TestsList;

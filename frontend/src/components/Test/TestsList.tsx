import { FC, useEffect } from 'react';
import { Button, Card, Col, Row, Table } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import { useRootStore } from '../../RootStateContext';
import { observer } from 'mobx-react-lite';
import MyPagination from '../UI/MyPagination';

interface TestsListProps {
  home?: boolean;
}

const TestsList: FC<TestsListProps> = observer(({ home }) => {
  const { testListStore } = useRootStore();
  const page = 1;

  useEffect(() => {
    testListStore.fetchTests('?' + 'page=' + page + (home ? '&itemsPerPage=5' : ''));
  }, []);

  const computeStart = (current, total) => {
    if (total - current < 3) {
      return total - 4;
    } else {
      return current === 1 ? 1 : current - 1;
    }
  };

  const paginate = (current, total) => {
    const items = [];
    const start = computeStart(current, total);
    for (let number = 0; number < 5; number++) {
      items.push(start + number);
    }
    return items;
  };

  return (
    <div>
      <Table>
        <thead hidden={testListStore.isEmpty()}>
          <tr>
            <th>Название</th>
            <th>сдало / прошло</th>
            <th />
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
          <div hidden={home}>
            <MyPagination view={testListStore.pagination} numbers={paginate(page, testListStore.totalPages())} />
          </div>
          <Button hidden={!home}>
            <Link className="link" to="/tests">
              Смотреть все тесты
            </Link>
          </Button>
        </Col>
        <Col className="col-sm-4">
          <p className="text-end me-3">Найдено {testListStore.totalItems} тестов</p>
        </Col>
      </Row>
    </div>
  );
});

export default TestsList;

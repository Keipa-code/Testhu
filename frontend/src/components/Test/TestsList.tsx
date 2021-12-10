import { FC, useEffect, useState } from 'react';
import { Button, Card, Col, Row, Table } from 'react-bootstrap';
import { Link, useHistory } from 'react-router-dom';
import { useRootStore } from '../../RootStateContext';
import { observer } from 'mobx-react-lite';
import MyPagination from '../UI/MyPagination';
import Loader from '../UI/Loader/Loader';
import { useQuery } from '../../hooks/useQuery';

interface TestsListProps {
  home?: boolean;
}

const TestsList: FC<TestsListProps> = observer(({ home }) => {
  const { testListStore } = useRootStore();
  const query = useQuery();
  const router = useHistory();
  const [page, setPage] = useState(Number(query.get('page')) ?? 1);

  const limit = home ? 5 : 20;

  useEffect(() => {
    testListStore.fetchTests(page, limit);
  }, []);

  const changePage = (pageNumber) => {
    setPage(pageNumber);
    testListStore.fetchTests(pageNumber, limit);
    query.set('page', String(pageNumber));
    router.push('?' + query.toString());
  };

  return (
    <div>
      {testListStore.isLoading ? (
        <div className="d-flex justify-content-center">
          <Loader />
        </div>
      ) : (
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
                <MyPagination pages={testListStore.pagination} changePage={changePage} />
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
          <Button
            onClick={() => {
              console.log(testListStore);
              console.log(page);
            }}
          >
            Проверка
          </Button>
        </div>
      )}
    </div>
  );
});

export default TestsList;

import { ChangeEvent, FC, useEffect, useState } from 'react';
import { Button, Card, Col, Row, Table, Form } from 'react-bootstrap';
import { Link, useHistory } from 'react-router-dom';
import { useRootStore } from '../../RootStateContext';
import { observer } from 'mobx-react-lite';
import MyPagination from '../UI/MyPagination';
import Loader from '../UI/Loader/Loader';
import { useQuery } from '../../hooks/useQuery';
import { message } from 'antd';

interface TestsListProps {
  home?: boolean;
}

const searchLengthWarn = 'В строку поиска больше 50 символов не вводите';

const TestsList: FC<TestsListProps> = observer(({ home }) => {
  const { testListStore } = useRootStore();
  const query = useQuery();
  const router = useHistory();
  const decomposeSort = (value: string) => {
    return [value.slice(0, value.indexOf('=')), value.slice(value.indexOf('=') + 1)] as const;
  };
  const [page, setPage] = useState(query.get('page') ? +query.get('page') : 1);
  const [sort, setSort] = useState(query.get('sort') ?? 'id');
  const [order, setOrder] = useState(query.get('order') ?? 'asc');
  const [search, setSearch] = useState(query.get('search') ?? '');
  const [tag, setTag] = useState(query.get('tag') ?? '');

  const limit = home ? 5 : 20;

  useEffect(() => {
    testListStore.fetchTests(page, limit, sort, order, search, tag);
  }, []);

  const changePage = (pageNumber) => {
    setPage(pageNumber);
    testListStore.fetchTests(pageNumber, limit, sort, order, search, tag);
    query.set('page', String(pageNumber));
    router.push('?' + query.toString());
  };

  const changeSort = (e: ChangeEvent<HTMLSelectElement>) => {
    const [selectedSort, selectedOrder] = decomposeSort(e.target.value);
    setSort(selectedSort);
    setOrder(selectedOrder);
    testListStore.fetchTests(1, limit, selectedSort, selectedOrder, search, tag);
    query.set('sort', selectedSort);
    query.set('order', selectedOrder);
    query.set('page', '1');
    router.push('?' + query.toString());
  };

  const find = (e) => {
    e.preventDefault();
    if (search.length < 80) {
      testListStore.fetchTests(1, limit, 'id', 'asc', search, tag);
      query.set('search', search);
      router.push('?' + query.toString());
    } else {
      message.warn(searchLengthWarn);
    }
  };

  const findByTag = (tagName) => {
    setTag(tagName);
    testListStore.fetchTests(1, limit, 'id', 'asc', search, tagName);
    query.set('tag', tagName);
    router.push('?' + query.toString());
  };

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    setSearch(e.target.value);
  };

  const handleTest = () => {
    console.log(testListStore);
  };

  return (
    <div>
      {testListStore.isLoading() ? (
        <div className="d-flex justify-content-center">
          <Loader />
        </div>
      ) : (
        <div>
          <Row className="mb-3">
            <Col className="col-sm-10 flex-fill">
              <Form onSubmit={find}>
                <Form.Control onChange={handleChange} defaultValue={search} placeholder="Введите название для поиска" />
              </Form>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col className="col-sm-4">
              <Form.Select size="sm" aria-label="Sort selector" onChange={changeSort} defaultValue={sort + '=' + order}>
                <option value="id=asc">От новых к старым</option>
                <option value="id=desc">От старых к новым</option>
                <option value="testName=asc">По названию (А-Я)</option>
                <option value="testName=desc">По названию (Я-А)</option>
                <option value="passed=asc">Сдавшие (по возрастанию)</option>
                <option value="passed=desc">Сдавшие (по уменьшению)</option>
                <option value="done=asc">Прошедшие (по возрастанию)</option>
                <option value="done=desc">Прошедшие (по уменьшению)</option>
              </Form.Select>
            </Col>
          </Row>
          <Table responsive="md">
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
                    <Card>
                      <Card.Body>
                        <Card.Title>{test.testName}</Card.Title>
                        {test.tags.map((tag) => (
                          <Card.Link
                            key={tag.id}
                            onClick={() => {
                              findByTag(tag.tagName);
                            }}
                          >
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
              handleTest();
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

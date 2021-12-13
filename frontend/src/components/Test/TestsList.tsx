import { ChangeEvent, FC, useEffect, useState } from 'react';
import { Row, message, Col, Button, Select, Form, Input, Table, Card, Pagination } from 'antd';
import { Link, useHistory } from 'react-router-dom';
import { useRootStore } from '../../RootStateContext';
import { observer } from 'mobx-react-lite';
import Loader from '../UI/Loader/Loader';
import { useQuery } from '../../hooks/useQuery';
import { ITestList } from '../../types/types';

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
  const [limit, setLimit] = useState(home ? 5 : 20);

  useEffect(() => {
    testListStore.fetchTests(page, limit, sort, order, search, tag);
  }, []);

  const changePage = (pageNumber, pageSize?) => {
    setPage(pageNumber);
    setLimit(pageSize ?? limit);
    testListStore.fetchTests(pageNumber, pageSize ?? limit, sort, order, search, tag);
    query.set('page', String(pageNumber));
    router.push('?' + query.toString());
  };

  const changeSort = (value: string) => {
    const [selectedSort, selectedOrder] = decomposeSort(value);
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

  const columns = [
    {
      title: 'Название теста',
      dataIndex: 'test',
      key: 'test',
      render: (test: ITestList) => (
        <Card
          title={test.testName}
          extra={<a href="#">More</a>}
          actions={test.tags.map((tag) => (
            <a
              key={tag.id}
              onClick={() => {
                findByTag(tag.tagName);
              }}
            >
              {tag.tagName}
            </a>
          ))}
        >
          {test.description.slice(0, 100) + ' ...'}
        </Card>
      ),
    },
    {
      title: 'Сдали',
      dataIndex: 'passed',
      key: 'passed',
    },
    {
      title: 'Прошли',
      dataIndex: 'done',
      key: 'done',
    },
  ];

  const dataSource = () => {
    return testListStore.tests.map((test) => ({
      test: test,
      passed: test.passed,
      done: test.done,
    }));
  };

  return (
    <div>
      {testListStore.isLoading() ? (
        <div className="d-flex justify-content-center">
          <Loader />
        </div>
      ) : (
        <div>
          <Row hidden={home}>
            <Col flex="auto">
              <Form onFinish={find}>
                <Form.Item>
                  <Input onChange={handleChange} defaultValue={search} placeholder="Введите название для поиска" />
                </Form.Item>
              </Form>
            </Col>
          </Row>
          <Row className="mb-3" hidden={home}>
            <Col span={6}>
              <Select
                className="select"
                aria-label="Sort selector"
                onChange={changeSort}
                defaultValue={sort + '=' + order}
              >
                <option value="id=asc">От новых к старым</option>
                <option value="id=desc">От старых к новым</option>
                <option value="testName=asc">По названию (А-Я)</option>
                <option value="testName=desc">По названию (Я-А)</option>
                <option value="passed=asc">Сдавшие (по возрастанию)</option>
                <option value="passed=desc">Сдавшие (по уменьшению)</option>
                <option value="done=asc">Прошедшие (по возрастанию)</option>
                <option value="done=desc">Прошедшие (по уменьшению)</option>
              </Select>
            </Col>
          </Row>
          <Table dataSource={dataSource()} columns={columns} pagination={false} />
          <Row className="align-items-center">
            <Col>
              <div hidden={home}>
                <Pagination
                  showQuickJumper
                  defaultCurrent={+testListStore.pagination.current}
                  total={testListStore.totalItems}
                  onChange={changePage}
                  pageSize={limit}
                  responsive={true}
                  onShowSizeChange={changePage}
                  showTotal={(total) => `Найдено ${total} тестов`}
                />
              </div>
              <Button hidden={!home}>
                <Link className="link" to="/tests">
                  Смотреть все тесты
                </Link>
              </Button>
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

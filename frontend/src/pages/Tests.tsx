import { Button, Col, Row } from 'antd';
import TestsList from '../components/Test/TestsList';
import { observer } from 'mobx-react-lite';

const Tests = observer(() => {
  return (
    <div className="container">
      <Row gutter={16} className="mb-3">
        <Col className="gutter-row" flex="auto">
          <h2>Поиск тестов</h2>
          <TestsList home={false} />
        </Col>
        <Col className="gutter-row" span={6}>
          <p className="mt-auto">
            Чтобы найти нужный вам тест, введите тему, имя пользователя или ключевые слова в поле поиска. Тесты
            выводятся по убыванию популярности.
          </p>
          <Button className="mb-3">Создать свой тест</Button>
          <br />
          <Button>Зарегистрироваться</Button>
        </Col>
      </Row>
    </div>
  );
});

export default Tests;

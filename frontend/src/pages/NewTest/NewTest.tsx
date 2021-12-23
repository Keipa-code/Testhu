import { MouseEvent, ChangeEvent, FC, useEffect, useState } from 'react';
import { Button, Checkbox, Col, Form, Input, Row, Spin } from 'antd';
import { observer } from 'mobx-react-lite';
import { useRootStore } from '../../RootStateContext';
import QuestionFormList from '../../components/Question/QuestionFormList';
import TagsForm from '../../components/TagsForm/TagsForm';
import { useHistory } from 'react-router-dom';

const NewTest: FC = observer(() => {
  const { newTestStore } = useRootStore();
  const { tagsFormStore } = useRootStore();
  const { questionFormStore } = useRootStore();
  const [showDescription, setShowDescription] = useState(false);
  const [showRules, setShowRules] = useState(false);
  const [showWrongAnswers, setShowWrongAnswers] = useState(false);
  const [resultIsPublic, setResultIsPublic] = useState(false);
  const router = useHistory();

  useEffect(() => {
    newTestStore.getFromStorage('newTest');
    setShowRules(Boolean(newTestStore.test.rules));
    setShowDescription(Boolean(newTestStore.test.description));
    const interval = setInterval(() => newTestStore.setToStorage('newTest'), 360000);
    return () => clearInterval(interval);
  }, []);

  const handleChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    newTestStore.inputChange(e.target.value, e.target.name);
  };

  const postTest = (e: MouseEvent<HTMLInputElement>) => {
    e.preventDefault();
    newTestStore.addTags(tagsFormStore.selectedTags);
    newTestStore.booleanChange(resultIsPublic, 'isPublic');
    newTestStore.booleanChange(showWrongAnswers, 'isWrongAnswersVisible');
    newTestStore
      .postNewTest()
      .then((res: any) => {
        questionFormStore.postQuestions(res.id);
      })
      .then(() => {
        newTestStore.loading = false;
        router.push(`/publish/${newTestStore.test.id}/${newTestStore.test.token}`);
      });
  };

  const tailFormItemLayout = {
    wrapperCol: {
      xs: {
        span: 24,
        offset: 0,
      },
      sm: {
        span: 16,
        offset: 5,
      },
    },
  };

  return (
    <div className="container">
      <Row>
        <Col className="ant-col-sm-18 ant-label">
          <h2 className="mb-5">Создать новый тест</h2>
          <Form labelCol={{ span: 5 }} wrapperCol={{ span: 15 }} layout="horizontal">
            <Form.Item label="Название теста">
              <Input
                name="testName"
                placeholder="Введите название"
                value={newTestStore.test.testName}
                onChange={handleChange}
              />
            </Form.Item>
            <Form.Item label="Тэги">
              <TagsForm />
            </Form.Item>
            <Form.Item hidden={!showDescription} label="Описание теста">
              <Input.TextArea
                rows={4}
                name="description"
                placeholder="Введите описание"
                value={newTestStore.test.description}
                onChange={handleChange}
              />
            </Form.Item>
            <Form.Item {...tailFormItemLayout}>
              <Button
                danger={showDescription}
                onClick={() => {
                  setShowDescription(!showDescription);
                }}
              >
                {showDescription ? 'Убрать описание' : 'Добавить описание'}
              </Button>
            </Form.Item>
            <Form.Item hidden={!showRules} label="Правила теста">
              <Input.TextArea
                rows={4}
                name="rules"
                placeholder="Введите правила теста"
                value={newTestStore.test.rules}
                onChange={handleChange}
              />
            </Form.Item>
            <Form.Item {...tailFormItemLayout}>
              <Button
                danger={showRules}
                onClick={() => {
                  setShowRules(!showRules);
                }}
              >
                {showRules ? 'Убрать правила' : 'Добавить правила'}
              </Button>
            </Form.Item>
            <Form.Item label="Ограничение по времени">
              <Input
                className="width-100"
                addonAfter="ч."
                name="hour"
                type="number"
                value={newTestStore.test.timeLimit.hour}
                placeholder="часы"
                onChange={handleChange}
              />
              <Input
                className="width-100"
                addonAfter="м."
                name="minute"
                type="number"
                value={newTestStore.test.timeLimit.minute}
                placeholder="минуты"
                onChange={handleChange}
              />
            </Form.Item>
            <Form.Item {...tailFormItemLayout}>
              <Checkbox
                onChange={() => {
                  setShowWrongAnswers(!showWrongAnswers);
                }}
              >
                Разрешить смотреть список неправильных ответов после теста
              </Checkbox>
            </Form.Item>
            <Form.Item {...tailFormItemLayout}>
              <Checkbox
                onChange={() => {
                  setResultIsPublic(!resultIsPublic);
                }}
              >
                Сделать все результаты прохождения публичными
              </Checkbox>
            </Form.Item>
          </Form>
          <QuestionFormList />
        </Col>
        <Col className="ant-col-sm-6">Текст</Col>
      </Row>
      <Row className="ant-row-end">
        <Col className="mb-5 ant-col-sm-10">
          <Button className="text-align-right" onClick={postTest} disabled={newTestStore.loading}>
            Сохранить
          </Button>
          {newTestStore.loading ?? <Spin />}
          <Button
            onClick={() => {
              newTestStore.test.id = undefined;
              console.log(newTestStore.test);
            }}
          >
            Печать
          </Button>
        </Col>
      </Row>
    </div>
  );
});

export default NewTest;

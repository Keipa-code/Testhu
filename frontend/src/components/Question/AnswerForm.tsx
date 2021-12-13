import React, { FC, useEffect, useState } from 'react';
import { Button, Checkbox, Col, Form, Input, List, Row } from 'antd';
import { useRootStore } from '../../RootStateContext';
import { observer } from 'mobx-react-lite';
import { CloseOutlined } from '@ant-design/icons';

type AnswerFormProps = {
  qKey?: number;
};

const AnswerForm: FC<AnswerFormProps> = observer(({ qKey }) => {
  const { questionFormStore } = useRootStore();
  const [answer, setAnswer] = useState('');
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    questionFormStore.questions[qKey].answers.length === 0 ? setVisible(false) : setVisible(true);
  }, []);

  // Добавить ограничение на максимальное количество вариантов - 5
  const handleAddAnswer = (e: React.MouseEvent<HTMLInputElement>) => {
    e.preventDefault();
    questionFormStore.addAnswer(qKey, answer);
    setAnswer('');
    setVisible(true);
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setAnswer(e.target.value);
  };

  const handleRemoveAnswer = (text) => {
    setVisible(questionFormStore.removeAnswer(qKey, text));
  };

  return (
    <div>
      <Form layout="vertical">
        <Row>
          <Col>
            <Form.Item label="Вариант ответа">
              <Input placeholder="Введите текст варианта ответа" value={answer} onChange={handleChange} />
            </Form.Item>
          </Col>
          <Col>
            <Button type="primary" onClick={handleAddAnswer}>
              Добавить
            </Button>
          </Col>
        </Row>
      </Form>
      <p className={!visible ? 'visually-hidden' : ''}>
        Поставьте галочку возле правильного ответа. Можете выбрать несколько правильных ответов.
      </p>
      <List
        itemLayout="horizontal"
        dataSource={questionFormStore.questions[qKey].answers}
        renderItem={(answer) => (
          <List.Item
            key={answer.text}
            actions={[
              <CloseOutlined
                key="list-remove"
                onClick={() => {
                  handleRemoveAnswer(answer.text);
                }}
              />,
            ]}
          >
            <div>
              <Checkbox
                defaultChecked={answer.correct}
                onChange={() => {
                  questionFormStore.answerCheckedChange(qKey, answer.text);
                }}
              />
              {answer.text}
            </div>
          </List.Item>
        )}
      />
    </div>
  );
});

export default AnswerForm;

import { useEffect } from 'react';
import { useRootStore } from '../../RootStateContext';
import QuestionFormItem from './QuestionFormItem';
import { Button, Collapse } from 'antd';
import { observer } from 'mobx-react-lite';
import { CloseOutlined } from '@ant-design/icons';

const QuestionFormList = observer(() => {
  const { questionFormStore } = useRootStore();
  const { Panel } = Collapse;

  useEffect(() => {
    questionFormStore.getFromStorage('newQuestions');
    const interval = setInterval(() => questionFormStore.setToStorage('newQuestions'), 360000);
    return () => clearInterval(interval);
  }, []);

  const handleClick = () => {
    questionFormStore.addQuestion();
  };

  const handleRemove = (index: number) => {
    return (
      <CloseOutlined
        onClick={() => {
          questionFormStore.removeQuestion(index);
        }}
      />
    );
  };

  return (
    <div>
      <h2 className="mb-3">Вопросы теста</h2>
      <Collapse accordion>
        {questionFormStore.questions.map((question, qKey) => (
          <Panel
            header={'Вопрос № ' + (qKey + 1)}
            key={question.questionText}
            id={String(qKey)}
            extra={handleRemove(qKey)}
          >
            <QuestionFormItem qKey={qKey} question={question} inputChange={questionFormStore.inputChange} />
          </Panel>
        ))}
      </Collapse>
      <Button onClick={handleClick}>Добавить вопрос</Button>
    </div>
  );
});

export default QuestionFormList;
